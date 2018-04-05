<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * CubicheCoreExtension class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CubicheCoreExtension extends AbstractExtension
{
    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    protected $separatorMap = array();

    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    protected $aliasMap = array();

    /**
     * Used inside metadata driver method to simplify aggregation of data.
     */
    protected $drivers = array();

    /**
     * {@inheritdoc}
     */
    public function configure(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->processMongoDBConfiguration($config['mongodb'], $container);
        $this->processMetadataConfiguration($config['metadata'], $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processMongoDBConfiguration(array $config, ContainerBuilder $container)
    {
        $this->processConnectionsConfiguration($config['connections'], $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processConnectionsConfiguration(array $config, ContainerBuilder $container)
    {
        $container->setParameter('cubiche.mongodb.connection.default.server', $config['default']['server']);
        $container->setParameter('cubiche.mongodb.connection.default.database', $config['default']['database']);

        $container->setParameter('cubiche.mongodb.connection.event_store.server', $config['event_store']['server']);
        $container->setParameter(
            'cubiche.mongodb.connection.event_store.database',
            $config['event_store']['database']
        );

        $container->setParameter(
            'cubiche.mongodb.connection.snapshot_store.server',
            $config['snapshot_store']['server']
        );

        $container->setParameter(
            'cubiche.mongodb.connection.snapshot_store.database',
            $config['snapshot_store']['database']
        );
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    protected function processMetadataConfiguration(array $config, ContainerBuilder $container)
    {
        $cacheDirectory = $container->getParameter('kernel.cache_dir').'/metadata';
        if (isset($config['cache_dir'])) {
            $cacheDirectory = $config['cache_dir'];
        }

        $container->setParameter('cubiche.metadata.cache_directory', $cacheDirectory);

        $this->loadMappingInformation($config['mappings'], $container);
        $this->registerMappingDrivers($container);
    }

    /**
     * Register all the collected mapping information by registering the appropriate mapping drivers.
     *
     * @param ContainerBuilder $container
     */
    protected function registerMappingDrivers(ContainerBuilder $container)
    {
        if ($container->hasDefinition($this->getElementName('metadata_driver'))) {
            $chainDriverDef = $container->getDefinition($this->getElementName('metadata_driver'));
        } else {
            $chainDriverDef = new Definition('%'.$this->getElementName('metadata.driver_chain.class%'));
            $chainDriverDef->setPublic(false);
        }

        if ($container->hasDefinition($this->getElementName('metadata_factory'))) {
            $metadataFactoryDef = $container->getDefinition($this->getElementName('metadata_factory'));
        } else {
            $metadataFactoryDef = new Definition(
                '%'.$this->getElementName('metadata.factory.class%'),
                array(
                    $chainDriverDef,
                    new Reference($this->getElementName('metadata.cache')),
                )
            );
        }

        foreach ($this->aliasMap as $alias => $prefix) {
            foreach ($this->drivers as $driverType => $driverPaths) {
                $mappingService = $this->getElementName($alias.'_'.$driverType.'_metadata_driver');
                $locatorService = $this->getElementName($alias.'_'.$driverType.'_metadata_locator');

                if ($container->hasDefinition($mappingService)) {
                    $mappingDriverDef = $container->getDefinition($mappingService);
                    $locatorDef = $container->getDefinition($locatorService);

                    if ('xml' == $driverType || 'yml' == $driverType) {
                        $args = $locatorDef->getArguments();
                        $args[0] = array_merge(array_values($driverPaths), $args[0]);

                        $locatorDef->setArguments($args);
                    } else {
                        $args = $mappingDriverDef->getArguments();
                        $args[1] = array_merge(array_values($driverPaths), $args[1]);

                        $mappingDriverDef->setArguments($args);
                    }
                } else {
                    if ('annotation' == $driverType) {
                        $mappingDriverDef = new Definition(
                            '%'.$this->getElementName('metadata.'.$driverType.'.class%'),
                            array(
                                new Reference($this->getElementName('metadata.cached_reader')),
                                array_values($driverPaths),
                            )
                        );
                    } else {
                        $locatorDef = new Definition(
                            '%'.$this->getElementName('metadata.default_locator.class%'),
                            array(array_flip($driverPaths), $this->separatorMap[$driverType])
                        );

                        $mappingDriverDef = new Definition(
                            '%'.$this->getElementName('metadata.'.$driverType.'.class%'),
                            array($locatorDef)
                        );
                    }
                }

                $locatorDef->setPublic(false);
                $mappingDriverDef->setPublic(false);

                $container->setDefinition($mappingService, $mappingDriverDef);
                foreach ($driverPaths as $prefix => $driverPath) {
                    $chainDriverDef->addMethodCall('addDriver', array(new Reference($mappingService)));
                }
            }
        }

        $container->setDefinition($this->getElementName('metadata_driver'), $chainDriverDef);
        $container->setDefinition($this->getElementName('metadata_factory'), $metadataFactoryDef);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @throws \InvalidArgumentException
     */
    protected function loadMappingInformation(array $config, ContainerBuilder $container)
    {
        foreach ($config as $mappingName => $mappingConfig) {
            $mappingConfig = array_replace(array(
                'dir' => false,
                'type' => false,
                'prefix' => false,
            ), (array) $mappingConfig);

            $mappingConfig['dir'] = $container->getParameterBag()->resolveValue($mappingConfig['dir']);

            $this->assertValidMappingConfiguration($mappingConfig);
            $this->setMappingDriverConfig($mappingConfig, $mappingName);
            $this->setMappingDriverAlias($mappingConfig, $mappingName);
        }
    }

    /**
     * Assertion if the specified mapping information is valid.
     *
     * @param array $mappingConfig
     *
     * @throws \InvalidArgumentException
     */
    protected function assertValidMappingConfiguration(array $mappingConfig)
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
                'Can only configure "xml", "yml" or "annotation" through the CubicheBundle. '.
                'Use your own bundle to configure other metadata drivers. '
            );
        }
    }

    /**
     * Register the mapping driver configuration for later use with the metadata driver chain.
     *
     * @param array  $mappingConfig
     * @param string $mappingName
     *
     * @throws \InvalidArgumentException
     */
    protected function setMappingDriverConfig(array $mappingConfig, $mappingName)
    {
        $mappingDirectory = $mappingConfig['dir'];
        if (!is_dir($mappingDirectory)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid mapping path given. Cannot load mapping named "%s".', $mappingName)
            );
        }

        $type = $mappingConfig['type'];
        $prefix = $mappingConfig['prefix'];

        $this->drivers[$type][$prefix] = realpath($mappingDirectory) ?: $mappingDirectory;
    }

    /**
     * Register the alias for this mapping driver.
     *
     * @param array  $mappingConfig
     * @param string $mappingName
     */
    protected function setMappingDriverAlias($mappingConfig, $mappingName)
    {
        $this->aliasMap[$mappingName] = $mappingConfig['prefix'];
        $this->separatorMap[$mappingConfig['type']] = $mappingConfig['separator'];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getElementName($name)
    {
        return 'cubiche.'.$name;
    }
}
