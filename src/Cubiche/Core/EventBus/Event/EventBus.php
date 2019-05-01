<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventBus\Event;

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\EventBus\Middlewares\EventDispatcher\EventDispatcherMiddleware;
use Cubiche\Core\EventDispatcher\EventDispatcher;
use Cubiche\Core\EventDispatcher\EventSubscriberInterface;

/**
 * EventBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBus extends Bus
{
    /**
     * @return EventBus
     */
    public static function create(EventSubscriberInterface ...$eventSubscribers)
    {
        return new static([
            250 => new LockingMiddleware(),
            100 => new EventDispatcherMiddleware(new EventDispatcher(
                new ClassBasedNameResolver(),
                ...$eventSubscribers
            )),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $event)
    {
        if (!$event instanceof EventInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    EventInterface::class,
                    get_class($event)
                )
            );
        }

        parent::dispatch($event);
    }
}
