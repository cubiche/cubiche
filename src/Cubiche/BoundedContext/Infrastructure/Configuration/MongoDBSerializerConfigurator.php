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
use Cubiche\Core\Serializer\Handler\HandlerManager;
use Cubiche\Core\Serializer\Handler\ObjectMetadataHandler;
use Cubiche\Core\Serializer\Serializer;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Core\Serializer\Visitor\VisitorNavigator;
use Psr\Container\ContainerInterface;
use function DI\get;
use function DI\create;

/**
 * MongoDBSerializerConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBSerializerConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.mongodb.serializer.handlers' => function (ContainerInterface $container) {
                return [
                    new ObjectMetadataHandler($container->get('app.mongodb.metadata.factory'))
                ];
            },
            'app.mongodb.serializer.handler_manager' => function (ContainerInterface $container) {
                $handlerManager = new HandlerManager();

                // configure default handlers
                $handlers = $container->get('app.serializer.handlers');
                foreach ($handlers as $handler) {
                    $handlerManager->addHandler($handler);
                }

                // configure mongodb handlers
                $handlers = $container->get('app.mongodb.serializer.handlers');
                foreach ($handlers as $handler) {
                    $handlerManager->addHandler($handler);
                }

                return $handlerManager;
            },
            'app.mongodb.serializer' => function(ContainerInterface $container) {
                return new Serializer(
                    $container->get('app.mongodb.serializer.visitor_navigator'),
                    $container->get('app.serializer.visitor_serialization'),
                    $container->get('app.serializer.visitor_deserialization')
                );
            },
            'app.mongodb.serializer.visitor_navigator' => create(VisitorNavigator::class)
                // to avoid the circular reference with the event bus
                ->constructor(
                    get('app.mongodb.serializer.handler_manager'),
                    get('app.event_bus')
                )->lazy()
        ];
    }
}
