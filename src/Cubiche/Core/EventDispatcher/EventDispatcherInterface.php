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
     *
     * @param $event
     *
     * @return EventInterface
     */
    public function dispatch($event);

    /**
     * Gets the list of event listeners.
     *
     * @return ArrayHashMap
     */
    public function listeners();

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string $eventName
     *
     * @return SortedArrayHashMap
     */
    public function eventListeners($eventName);

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
    public function listenerPriority($eventName, callable $listener);

    /**
     * Checks whether has any registered listener.
     *
     * @return bool
     */
    public function hasListeners();

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasEventListeners($eventName);

    /**
     * Adds an event listener that listens on the specified events. The higher priority value, the earlier an event
     * listener will be triggered in the chain (defaults to 0).
     *
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     */
    public function addListener($eventName, callable $listener, $priority = 0);

    /**
     * Removes an event listener from the specified events.
     *
     * @param string   $eventName
     * @param callable $listener
     */
    public function removeListener($eventName, callable $listener);

    /**
     * Adds an event subscriber. The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function addSubscriber(EventSubscriberInterface $subscriber);

    /**
     * Removes an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber);
}
