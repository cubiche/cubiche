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

use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\EventBus\Middlewares\EventDispatcher\EventDispatcherMiddleware;
use Cubiche\Core\EventDispatcher\EventDispatcher;
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;

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
            'app.event_bus.subscribers' => [],
            'app.event_bus.middlewares' => [
                400 => create(LockingMiddleware::class),
                350 => create(EventDispatcherMiddleware::class)->constructor(
                    get('app.event_dispatcher')
                ),
            ],
            'app.event_dispatcher' => function (ContainerInterface $container) {
                return new EventDispatcher(
                    $container->get('app.bus.message_name_resolver'),
                    ...$container->get('app.event_bus.subscribers')
                );
            },
            'app.event_bus' => function (ContainerInterface $container) {
                return new EventBus($container->get('app.event_bus.middlewares'));
            }
        ];
    }
}
