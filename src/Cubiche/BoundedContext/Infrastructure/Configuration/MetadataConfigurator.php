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
use Cubiche\Core\Metadata\Cache\FileCache;
use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\Driver\ChainDriver;
use Cubiche\Core\Metadata\Driver\StaticDriver;
use Psr\Container\ContainerInterface;

/**
 * MetadataConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MetadataConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * MetadataConfigurator constructor.
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
        return [
            'app.metadata.parameter.cache_directory' => $this->config['cache_dir'],
            'app.metadata.cache' => function(ContainerInterface $container) {
                return new FileCache(
                    sprintf("%s/metadata", $container->get('app.metadata.parameter.cache_directory'))
                );
            },
            'app.metadata.factory' => function(ContainerInterface $container) {
                return new ClassMetadataFactory(
                    $container->get('app.metadata.driver'),
                    $container->get('app.metadata.cache')
                );
            },
            'app.metadata.driver' => function(ContainerInterface $container) {
                return new ChainDriver($this->createDrivers($container));
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
        return [
            $this->createStaticDriver(
                $this->config['static']['paths']['included'],
                $this->config['static']['paths']['excluded']
            )
        ];
    }

    /**
     * @param array $included
     * @param array $excluded
     *
     * @return StaticDriver
     */
    private function createStaticDriver(array $included, array $excluded)
    {
        $driver = new StaticDriver('loadMetadata', $included);
        $driver->addExcludePaths($excluded);
        $driver->setFileExtension('.php');

        return $driver;
    }
}
