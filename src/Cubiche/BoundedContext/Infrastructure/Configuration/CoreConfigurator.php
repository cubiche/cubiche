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
use Cubiche\Core\Bus\Async\Middlewares\MessagePublisherMiddleware;
use Cubiche\Core\Bus\Async\Publisher\Policy\AllwaysPublishMessagesExceptPolicy;
use Cubiche\Core\Bus\Async\Publisher\Policy\AllwaysPublishMessagesPolicy;
use Cubiche\Core\Serializer\Event\PostDeserializeEvent;
use Cubiche\Core\Serializer\Event\PostSerializeEvent;
use Cubiche\Core\Serializer\Event\PreDeserializeEvent;
use Cubiche\Core\Serializer\Event\PreSerializeEvent;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;
use Cubiche\Infrastructure\Bus\Async\Publisher\RabbitMQMessagePublisher;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\Definition\Processor;
use function DI\create;
use function DI\get;
use function DI\add;

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
                'app.bus.async_message_publisher.policy' => new AllwaysPublishMessagesPolicy(),
                'app.bus.async_message_publisher' => function (ContainerInterface $container) {
                    return new RabbitMQMessagePublisher($container->get('app.serializer'));
                },
                'app.event_bus.middlewares' => add([
                    300 => create(MessagePublisherMiddleware::class)->constructor(
                        get('app.bus.async_message_publisher'),
                        get('app.bus.async_message_publisher.policy')
                    )
                ]),
            ]
        );
    }
}
