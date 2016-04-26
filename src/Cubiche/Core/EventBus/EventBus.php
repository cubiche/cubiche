<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventBus;

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\EventBus\Exception\InvalidMiddlewareException;
use Cubiche\Core\EventBus\Exception\NotFoundException;
use Cubiche\Core\EventBus\Middlewares\Notifier\NotifierMiddleware;

/**
 * EventBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBus
{
    /**
     * @var NotifierMiddleware
     */
    protected $notifierMiddleware;

    /**
     * @var Delegate
     */
    protected $chainedMiddleware;

    /**
     * EventBus constructor.
     *
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->ensureNotifierMiddleware($middlewares);
        $this->chainedMiddleware = $this->chainedExecution($middlewares);
    }

    /**
     * Notify the given event.
     *
     * @param $event
     *
     * @return EventInterface
     */
    public function notify($event)
    {
        $event = $this->notifierMiddleware->notifier()->ensureEvent($event);
        $chainedMiddleware = $this->chainedMiddleware;

        $chainedMiddleware($event);

        return $event;
    }

    /**
     * Ensure that exists an notifier middleware.
     *
     * @param array $middlewares
     *
     * @throws InvalidArgumentException
     */
    protected function ensureNotifierMiddleware(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof NotifierMiddleware) {
                $this->notifierMiddleware = $middleware;

                return;
            }
        }

        throw NotFoundException::middlewareOfType(NotifierMiddleware::class);
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     *
     * @return Delegate
     */
    private function chainedExecution(array $middlewares)
    {
        $next = Delegate::fromClosure(function () {
            // the final middleware is empty
        });

        // reverse iteration over middlewares
        while ($middleware = array_pop($middlewares)) {
            if (!$middleware instanceof MiddlewareInterface) {
                throw InvalidMiddlewareException::forMiddleware($middleware, MiddlewareInterface::class);
            }

            $next = Delegate::fromClosure(function (EventInterface $event) use ($middleware, $next) {
                return $middleware->handle($event, $next);
            });
        }

        return $next;
    }

    /**
     * Adds an event listener that listens on the specified events. The higher priority value, the earlier an event
     * listener will be triggered in the chain (defaults to 0).
     *
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     *
     * @return $this
     */
    public function addListener($eventName, callable $listener, $priority = 0)
    {
        $this->notifierMiddleware->notifier()->addListener($eventName, $listener, $priority);
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param string   $eventName
     * @param callable $listener
     *
     * @return $this
     */
    public function removeListener($eventName, callable $listener)
    {
        $this->notifierMiddleware->notifier()->removeListener($eventName, $listener);
    }

    /**
     * Adds an event subscriber. The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     *
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->notifierMiddleware->notifier()->addSubscriber($subscriber);
    }

    /**
     * Removes an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->notifierMiddleware->notifier()->removeSubscriber($subscriber);
    }

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string $eventName
     *
     * @return array
     */
    public function listeners($eventName = null)
    {
        return $this->notifierMiddleware->notifier()->listeners($eventName);
    }

    /**
     * Gets the listener priority for a specific event.
     *
     * Returns null if the event or the listener does not exist.
     *
     * @param string   $eventName
     * @param callable $listener
     *
     * @return int
     */
    public function listenerPriority($eventName, callable $listener)
    {
        return $this->notifierMiddleware->notifier()->listenerPriority($eventName, $listener);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasListeners($eventName = null)
    {
        return $this->notifierMiddleware->notifier()->hasListeners($eventName);
    }
}
