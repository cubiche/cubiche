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
            }
        ];
    }
}
