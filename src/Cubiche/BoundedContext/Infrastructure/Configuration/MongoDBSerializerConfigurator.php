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
            'app.mongodb.serializer' => function(ContainerInterface $container) {
                return new Serializer(
                    $container->get('app.mongodb.serializer.visitor_navigator'),
                    $container->get('app.mongodb.serializer.visitor_serialization'),
                    $container->get('app.mongodb.serializer.visitor_deserialization')
                );
            },
            'app.mongodb.serializer.visitor_navigator' => create(VisitorNavigator::class)
                // to avoid the circular reference with the event bus
                ->constructor(
                    get('app.mongodb.metadata.factory'),
                    get('app.serializer.handler_manager'),
                    get('app.event_bus')
                )->lazy()
            ,
            'app.mongodb.serializer.visitor_serialization' => function(ContainerInterface $container) {
                return new SerializationVisitor(
                    $container->get('app.mongodb.serializer.visitor_navigator')
                );
            },
            'app.mongodb.serializer.visitor_deserialization' => function(ContainerInterface $container) {
                return new DeserializationVisitor(
                    $container->get('app.mongodb.serializer.visitor_navigator')
                );
            }
        ];
    }
}
