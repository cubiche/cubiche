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
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * EventBusFactory constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return EventBus
     */
    public function create()
    {
        return new EventBus([
            250 => new LockingMiddleware(),
            100 => new EventDispatcherMiddleware($this->dispatcher),
        ]);
    }
}
