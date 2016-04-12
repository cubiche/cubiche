<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventBus;

use Cubiche\Domain\EventBus\Exception\InvalidMiddlewareException;
use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\EventBus\Exception\NotFoundException;
use Cubiche\Domain\EventBus\Middlewares\Emitter\EmitterMiddleware;

/**
 * EventBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBus
{
    /**
     * @var EmitterMiddleware
     */
    protected $emitterMiddleware;

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
        $this->ensureEmitterMiddleware($middlewares);
        $this->chainedMiddleware = $this->chainedExecution($middlewares);
    }

    /**
     * Executes the given event and optionally returns a value.
     *
     * @param $event
     *
     * @return EventInterface
     */
    public function emit($event)
    {
        $event = $this->emitterMiddleware->emitter()->ensureEvent($event);
        $chainedMiddleware = $this->chainedMiddleware;

        $chainedMiddleware($event);

        return $event;
    }

    /**
     * Ensure that exists an emitter middleware.
     *
     * @param array $middlewares
     *
     * @throws InvalidArgumentException
     */
    protected function ensureEmitterMiddleware(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof EmitterMiddleware) {
                $this->emitterMiddleware = $middleware;

                return;
            }
        }

        throw NotFoundException::middleware(EmitterMiddleware::class);
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
        $this->emitterMiddleware->emitter()->addListener($eventName, $listener, $priority);
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
        $this->emitterMiddleware->emitter()->removeListener($eventName, $listener);
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
        $this->emitterMiddleware->emitter()->addSubscriber($subscriber);
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
        $this->emitterMiddleware->emitter()->removeSubscriber($subscriber);
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
        $this->emitterMiddleware->emitter()->listeners($eventName);
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
        $this->emitterMiddleware->emitter()->listenerPriority($eventName, $listener);
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
        $this->emitterMiddleware->emitter()->hasListeners($eventName);
    }
}
