<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Event;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\EventDispatcher\EventDispatcherMiddleware;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\EventDispatcher\EventDispatcher;

/**
 * EventBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBus extends Bus
{
    /**
     * @var EventDispatcherMiddleware
     */
    protected $dispatcherMiddleware;

    /**
     * @return EventBus
     */
    public static function create()
    {
        return new static([
            250 => new LockingMiddleware(),
            100 => new EventDispatcherMiddleware(new EventDispatcher()),
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

        $this->ensureEventDispatcherMiddleware();

        parent::dispatch($event);
    }

    /**
     * Ensure that exists an dispatcher middleware.
     *
     * @throws InvalidArgumentException
     */
    protected function ensureEventDispatcherMiddleware()
    {
        if ($this->dispatcherMiddleware !== null) {
            return;
        }

        foreach ($this->middlewares as $priority => $collection) {
            foreach ($collection as $middleware) {
                if ($middleware instanceof EventDispatcherMiddleware) {
                    $this->dispatcherMiddleware = $middleware;

                    return;
                }
            }
        }

        throw NotFoundException::middlewareOfType(EventDispatcherMiddleware::class);
    }

    /**
     * @return EventDispatcherMiddleware
     */
    public function dispatcherMiddleware()
    {
        $this->ensureEventDispatcherMiddleware();

        return $this->dispatcherMiddleware;
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
        $this->ensureEventDispatcherMiddleware();

        $this->dispatcherMiddleware->dispatcher()->addListener($eventName, $listener, $priority);
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
        $this->ensureEventDispatcherMiddleware();

        $this->dispatcherMiddleware->dispatcher()->removeListener($eventName, $listener);
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
        $this->ensureEventDispatcherMiddleware();

        $this->dispatcherMiddleware->dispatcher()->addSubscriber($subscriber);
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
        $this->ensureEventDispatcherMiddleware();

        $this->dispatcherMiddleware->dispatcher()->removeSubscriber($subscriber);
    }

    /**
     * Gets the list of event listeners.
     *
     * @return array
     */
    public function listeners()
    {
        $this->ensureEventDispatcherMiddleware();

        return $this->dispatcherMiddleware->dispatcher()->listeners();
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasEventListeners($eventName)
    {
        $this->ensureEventDispatcherMiddleware();

        return $this->dispatcherMiddleware->dispatcher()->hasEventListeners($eventName);
    }

    /**
     * Checks whether has any registered listener.
     *
     * @return bool
     */
    public function hasListeners()
    {
        $this->ensureEventDispatcherMiddleware();

        return $this->dispatcherMiddleware->dispatcher()->hasListeners();
    }
}
