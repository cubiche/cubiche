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
use Cubiche\Core\Serializer\Handler\CollectionHandler;
use Cubiche\Core\Serializer\Handler\CoordinateHandler;
use Cubiche\Core\Serializer\Handler\DateRangeHandler;
use Cubiche\Core\Serializer\Handler\DateTimeHandler;
use Cubiche\Core\Serializer\Handler\DateTimeValueObjectHandler;
use Cubiche\Core\Serializer\Handler\HandlerManager;
use Cubiche\Core\Serializer\Handler\LocalizableValueHandler;
use Cubiche\Core\Serializer\Handler\NativeValueObjectHandler;
use Cubiche\Core\Serializer\Handler\ObjectHandler;
use Cubiche\Core\Serializer\Handler\SerializableHandler;
use Cubiche\Core\Serializer\Serializer;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Core\Serializer\Visitor\VisitorNavigator;
use Psr\Container\ContainerInterface;
use function DI\get;
use function DI\create;

/**
 * SerializerConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class SerializerConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.serializer.handlers' => function (ContainerInterface $container) {
                return [
                    new CollectionHandler(),
                    new CoordinateHandler(),
                    new DateRangeHandler(),
                    new DateTimeHandler(),
                    new DateTimeValueObjectHandler(),
                    new LocalizableValueHandler(),
                    new NativeValueObjectHandler(),
                    new SerializableHandler(),
                    new ObjectHandler($container->get('app.metadata.factory'))
                ];
            },
            'app.serializer.handler_manager' => function (ContainerInterface $container) {
                $handlerManager = new HandlerManager();

                // configure handlers
                $handlers = $container->get('app.serializer.handlers');
                foreach ($handlers as $handler) {
                    $handlerManager->addHandler($handler);
                }

                return $handlerManager;
            },
            'app.serializer' => function(ContainerInterface $container) {
                return new Serializer(
                    $container->get('app.serializer.visitor_navigator'),
                    $container->get('app.serializer.visitor_serialization'),
                    $container->get('app.serializer.visitor_deserialization')
                );
            },
            'app.serializer.visitor_navigator' => create(VisitorNavigator::class)
                // to avoid the circular reference with the event bus
                ->constructor(
                    get('app.serializer.handler_manager'),
                    get('app.event_bus')
                )->lazy()
            ,
            'app.serializer.visitor_serialization' => function(ContainerInterface $container) {
                return new SerializationVisitor(
                    $container->get('app.serializer.visitor_navigator')
                );
            },
            'app.serializer.visitor_deserialization' => function(ContainerInterface $container) {
                return new DeserializationVisitor(
                    $container->get('app.serializer.visitor_navigator')
                );
            }
        ];
    }
}
