<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Configuration;

use Cubiche\Core\Bus\Publisher\MessagePublisher;
use Cubiche\Domain\EventSourcing\Factory\AggregateRepositoryFactory;
use Cubiche\Infrastructure\Cqrs\Factory\HandlerClassResolverFactory;
use Psr\Container\ContainerInterface;

/**
 * CoreConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CoreConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.bus.factory.handler_class_resolver' => function () {
                return new HandlerClassResolverFactory();
            },
            'app.repository.factory.event_sourced_aggregate' => function (ContainerInterface $container) {
                return new AggregateRepositoryFactory(
                    $container->get('app.event_store'),
                    $container->get('app.bus.message_publisher')
                );
            },
            'app.bus.message_publisher' => function (ContainerInterface $container) {
                return new MessagePublisher($container->get('app.event_bus'));
            },
        ];
    }
}
