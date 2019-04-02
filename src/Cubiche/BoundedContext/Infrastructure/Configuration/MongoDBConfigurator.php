<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Infrastructure\Configuration;

use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\Driver\ChainDriver;
use Cubiche\Core\Metadata\Locator\DefaultFileLocator;
use Cubiche\Infrastructure\EventSourcing\MongoDB\EventStore\MongoDBEventStore;
use Cubiche\Infrastructure\EventSourcing\MongoDB\Snapshot\MongoDBSnapshotStore;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use Cubiche\Infrastructure\MongoDB\Metadata\Driver\AnnotationDriver;
use Cubiche\Infrastructure\MongoDB\Metadata\Driver\XmlDriver;
use Cubiche\Infrastructure\MongoDB\Metadata\Driver\YamlDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use Psr\Container\ContainerInterface;

/**
 * MongoDBConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBConfigurator implements ConfiguratorInterface
{
    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    private $separatorMap = array();

    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    private $aliasMap = array();

    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    private $drivers = array();

    /**
     * @var array
     */
    private $config;

    /**
     * MongoDBConfigurator constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        $this->loadMappingInformation($this->config['mappings']);

        return array_merge(
            $this->parametersConfiguration($this->config['connections']),
            $this->servicesConfiguration()
        );
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function parametersConfiguration(array $config)
    {
        return [
            'app.mongodb.parameter.read_model.server' => $config['read_model']['server'],
            'app.mongodb.parameter.read_model.database' => $config['read_model']['database'],
            'app.mongodb.parameter.write_model.server' => $config['write_model']['server'],
            'app.mongodb.parameter.write_model.database' => $config['write_model']['database'],
            'app.mongodb.parameter.event_store.server' => $config['event_store']['server'],
            'app.mongodb.parameter.event_store.database' => $config['event_store']['database'],
            'app.mongodb.parameter.snapshot_store.server' => $config['snapshot_store']['server'],
            'app.mongodb.parameter.snapshot_store.database' => $config['snapshot_store']['database']
        ];
    }

    /**
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    private function loadMappingInformation(array $config)
    {
        foreach ($config as $mappingName => $mappingConfig) {
            $mappingConfig = array_replace(array(
                'dir' => false,
                'type' => false,
                'prefix' => false,
            ), (array) $mappingConfig);

            $this->assertValidMappingConfiguration($mappingConfig);
            $this->drivers[$mappingConfig['type']][$mappingConfig['prefix']] = realpath($mappingConfig['dir']);
            $this->aliasMap[$mappingName] = $mappingConfig['prefix'];
            $this->separatorMap[$mappingConfig['type']] = $mappingConfig['separator'];
        }
    }

    /**
     * Assertion if the specified mapping information is valid.
     *
     * @param array $mappingConfig
     *
     * @throws \InvalidArgumentException
     */
    private function assertValidMappingConfiguration(array $mappingConfig)
    {
        if (!$mappingConfig['type'] || !$mappingConfig['dir'] || !$mappingConfig['prefix']) {
            throw new \InvalidArgumentException(
                'Mapping definitions for metadata require at least the "type", "dir" and "prefix" options.'
            );
        }

        if (!is_dir($mappingConfig['dir'])) {
            throw new \InvalidArgumentException(
                sprintf('Specified non-existing directory "%s" as mapping source.', $mappingConfig['dir'])
            );
        }

        if (!in_array($mappingConfig['type'], array('xml', 'yml', 'annotation'))) {
            throw new \InvalidArgumentException(
                'Can only configure "xml", "yml" or "annotation".'
            );
        }
    }

    /**
     * @return array
     */
    private function servicesConfiguration()
    {
        return [
            // binding mongodb services
            'app.mongodb.metadata.factory' => function(ContainerInterface $container) {
                return new ClassMetadataFactory(
                    $container->get('app.mongodb.metadata.driver'),
                    $container->get('app.metadata.cache')
                );
            },
            'app.mongodb.metadata.driver' => function(ContainerInterface $container) {
                return new ChainDriver($this->createDrivers($container));
            },
            // binding mongodb connection
            'app.mongodb.connection.read_model' => function(ContainerInterface $container) {
                return new Connection(
                    $container->get('app.mongodb.parameter.read_model.server'),
                    $container->get('app.mongodb.parameter.read_model.database')
                );
            },
            'app.mongodb.connection.write_model' => function(ContainerInterface $container) {
                return new Connection(
                    $container->get('app.mongodb.parameter.write_model.server'),
                    $container->get('app.mongodb.parameter.write_model.database')
                );
            },
            'app.mongodb.connection.event_store' => function(ContainerInterface $container) {
                return new Connection(
                    $container->get('app.mongodb.parameter.event_store.server'),
                    $container->get('app.mongodb.parameter.event_store.database')
                );
            },
            'app.mongodb.connection.snapshot_store' => function(ContainerInterface $container) {
                return new Connection(
                    $container->get('app.mongodb.parameter.snapshot_store.server'),
                    $container->get('app.mongodb.parameter.snapshot_store.database')
                );
            },
            // binding mongodb dm/event stores
            'app.mongodb.read_model_document_manager' => function(ContainerInterface $container) {
                return new DocumentManager(
                    $container->get('app.mongodb.connection.read_model'),
                    $container->get('app.mongodb.metadata.factory'),
                    $container->get('app.mongodb.serializer'),
                    $container->get('app.logger')
                );
            },
            'app.mongodb.write_model_document_manager' => function(ContainerInterface $container) {
                return new DocumentManager(
                    $container->get('app.mongodb.connection.write_model'),
                    $container->get('app.mongodb.metadata.factory'),
                    $container->get('app.mongodb.serializer'),
                    $container->get('app.logger')
                );
            },
            'app.mongodb.event_store' => function(ContainerInterface $container) {
                return new MongoDBEventStore(
                    $container->get('app.mongodb.connection.event_store')
                );
            },
            'app.mongodb.snapshot_store' => function(ContainerInterface $container) {
                return new MongoDBSnapshotStore(
                    $container->get('app.mongodb.connection.snapshot_store')
                );
            },
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return array
     */
    private function createDrivers(ContainerInterface $container)
    {
        $configuration = array();
        foreach ($this->aliasMap as $alias => $prefix) {
            foreach ($this->drivers as $driverType => $driverPaths) {
                if (isset($configuration[$driverType])) {
                    if ($driverType == 'annotation') {
                        $configuration[$driverType] = array(
                            'paths' => array_merge(
                                array_values($driverPaths),
                                $configuration[$driverType]['paths']
                            )
                        );
                    } else {
                        $configuration[$driverType] = array(
                            'paths' => array_merge(
                                array_flip($driverPaths),
                                $configuration[$driverType]['paths']
                            ),
                            'separator' => $this->separatorMap[$driverType]
                        );
                    }
                } else {
                    if ($driverType == 'annotation') {
                        $configuration[$driverType] = array(
                            'paths' => array_values($driverPaths)
                        );
                    } else {
                        $configuration[$driverType] = array(
                            'paths' => array_flip($driverPaths),
                            'separator' => $this->separatorMap[$driverType]
                        );
                    }
                }
            }
        }

        $drivers = array();
        foreach ($configuration as $driverType => $config) {
            switch ($driverType) {
                case 'annotation';
                    $drivers[] = $this->createAnnotationDriver($container, $config['paths']);
                    break;
                case 'xml';
                    $drivers[] = $this->createXmlDriver($config['paths'], $config['separator']);
                    break;
                case 'yml';
                    $drivers[] = $this->createYamlDriver($config['paths'], $config['separator']);
                    break;
            }
        }

        return $drivers;
    }

    /**
     * @param ContainerInterface $container
     * @param array              $paths
     *
     * @return AnnotationDriver
     */
    private function createAnnotationDriver(ContainerInterface $container, array $paths)
    {
        $annotationReader = new AnnotationReader();
        AnnotationReader::addGlobalIgnoredName('required');
        AnnotationRegistry::registerLoader('class_exists');

        $reader = new CachedReader(
            $annotationReader,
            new FilesystemCache(
                sprintf("%s/annotation", $container->get('app.metadata.parameter.cache_directory'))
            )
        );

        return new AnnotationDriver($reader, $paths);
    }

    /**
     * @param array  $prefixes
     * @param string $separator
     *
     * @return YamlDriver
     */
    private function createYamlDriver(array $prefixes, $separator)
    {
        return new YamlDriver(
            new DefaultFileLocator($prefixes, $separator)
        );
    }

    /**
     * @param array  $prefixes
     * @param string $separator
     *
     * @return XmlDriver
     */
    private function createXmlDriver(array $prefixes, $separator)
    {
        return new XmlDriver(
            new DefaultFileLocator($prefixes, $separator)
        );
    }
}
