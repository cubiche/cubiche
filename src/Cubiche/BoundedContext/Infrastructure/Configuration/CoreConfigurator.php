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

use Cubiche\BoundedContext\Application\Configuration\ChainConfigurator;
use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use Symfony\Component\Config\Definition\Processor;
use function DI\get;

/**
 * CoreConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CoreConfigurator implements ConfiguratorInterface
{
    /**
     * @var ChainConfigurator
     */
    private $configurators;

    /**
     * CoreConfigurator constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $processor = new Processor();
        $configuration = $processor->processConfiguration(new Configuration(), $config);

        $this->configurators = new ChainConfigurator(
            $this->configurators($configuration)
        );
    }

    /**
     * @param array $configuration
     *
     * @return array
     */
    protected function configurators(array $configuration)
    {
        return [
            new SerializerConfigurator(),
            new MetadataConfigurator($configuration['metadata']),
            new MongoDBConfigurator($configuration['mongodb']),
            new MongoDBRepositoryConfigurator(),
            new MongoDBSerializerConfigurator(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return array_merge(
            $this->configurators->configuration(),
            // aliases
            [
                'app.repository.factory.aggregate' => get('app.mongodb.repository.factory.aggregate'),
                'app.repository.factory.model' => get('app.mongodb.repository.factory.document_query'),
                'app.event_store' => get('app.mongodb.event_store'),
            ]
        );
    }
}
