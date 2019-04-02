<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventBus\Factory;

use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\EventBus\Middlewares\EventDispatcher\EventDispatcherMiddleware;
use Cubiche\Core\EventDispatcher\EventDispatcherInterface;

/**
 * EventBusFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBusFactory
{
    /**
     * @param EventDispatcherInterface $dispatcher
     *
     * @return EventBus
     */
    public static function create(EventDispatcherInterface $dispatcher)
    {
        return new EventBus([
            250 => new LockingMiddleware(),
            100 => new EventDispatcherMiddleware($dispatcher),
//            100 => new EventRoutingMiddleware($dispatcher),
        ]);
    }
//    /**
//     * @param EventDispatcherInterface $dispatcher
//     *
//     * @return EventBus
//     */
//    public static function createAsync(EventDispatcherInterface $dispatcher)
//    {
//        return new EventBus([
//            250 => new LockingMiddleware(),
//            100 => new EventDispatcherMiddleware($dispatcher),
//        ]);
//    }
}
