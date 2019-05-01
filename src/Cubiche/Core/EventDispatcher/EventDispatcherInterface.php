<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventDispatcher;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\SortedArrayHashMap;

/**
 * EventDispatcher interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventDispatcherInterface
{
    /**
     * Emit an event to all registered listeners.
     */
    public function dispatch(EventInterface $event);

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     */
    public function eventListeners(string $eventName): array;

    /**
     * Gets the listener priority for a specific event.
     * Returns null if the event or the listener does not exist.
     *
     * @return int|null
     */
    public function listenerPriority(string $eventName, callable $listener): ?int;

    /**
     * Checks whether has any registered listener.
     */
    public function hasListeners(): bool;

    /**
     * Checks whether an event has any registered listeners.
     */
    public function hasEventListeners(string $eventName): bool;

    /**
     * Adds an event listener that listens on the specified events. The higher priority value, the earlier an event
     * listener will be triggered in the chain (defaults to 0).
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0);

    /**
     * Removes an event listener from the specified events.
     */
    public function removeListener(string $eventName, callable $listener);

    /**
     * Adds an event subscriber. The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     */
    public function addSubscriber(EventSubscriberInterface $subscriber);

    /**
     * Removes an event subscriber.
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber);
}
