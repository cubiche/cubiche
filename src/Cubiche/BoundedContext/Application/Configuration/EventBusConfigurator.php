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

use Cubiche\Core\EventDispatcher\EventDispatcher;
use Cubiche\Infrastructure\EventBus\Factory\EventBusFactory;
use Psr\Container\ContainerInterface;

/**
 * EventBusConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class EventBusConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            'app.event_bus.handlers' => [],
            'app.event_bus.subscribers' => [],
            'app.event_dispatcher' => function (ContainerInterface $container) {
                $eventDispatcher = new EventDispatcher();

                // configure handlers
                $handlers = $container->get('app.event_bus.handlers');
                foreach ($handlers as $eventName => $listeners) {
                    foreach ($listeners as $listener) {
                        if (!is_array($listener) || !isset($listener['handler']) || !isset($listener['method'])) {
                            throw new \InvalidArgumentException(
                                sprintf(
                                    'The %s handler should define the handler, method and priority (optional) values',
                                    $eventName
                                )
                            );
                        }

                        $eventDispatcher->addListener(
                            $eventName,
                            array($listener['handler'], $listener['method']),
                            $listener['priority'] ?? 0
                        );
                    }
                }

                // configure subscribers
                $subscribers = $container->get('app.event_bus.subscribers');
                foreach ($subscribers as $eventSubscriber) {
                    $eventDispatcher->addSubscriber($eventSubscriber);
                }

                return $eventDispatcher;
            },
            'app.event_bus' => function (ContainerInterface $container) {
                return EventBusFactory::create($container->get('app.event_dispatcher'));
            }
        ];
    }
}
