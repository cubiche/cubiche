<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Console\Api\Config;

use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Bus\Event\EventSubscriberInterface;

/**
 * CommandConfig trait.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait CommandConfigTrait
{
    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * Returns the event eventBus used to dispatch the console events.
     *
     * @return EventBus The event eventBus.
     */
    public function getEventBus()
    {
        return $this->eventBus;
    }

    /**
     * Sets the event eventBus for dispatching the console events.
     *
     * @param EventBus $eventBus The event eventBus.
     *
     * @return static The current instance.
     */
    public function setEventBus(EventBus $eventBus = null)
    {
        $this->eventBus = $eventBus;

        return $this;
    }

    /**
     * Adds a listener for the given event name.
     *
     * See {@link ConsoleEvents} for the supported event names.
     *
     * @param string   $eventName The event to listen to.
     * @param callable $listener  The callback to execute when the event is
     *                            dispatched.
     * @param int      $priority  The event priority.
     *
     * @return static The current instance.
     *
     * @see EventBus::addListener()
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        if (!$this->eventBus) {
            $this->eventBus = EventBus::create();
        }

        $this->eventBus->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * Adds an event subscriber to the eventBus.
     *
     * @param EventSubscriberInterface $subscriber The subscriber to add.
     *
     * @return static The current instance.
     *
     * @see EventBus::addSubscriber()
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        if (!$this->eventBus) {
            $this->eventBus = EventBus::create();
        }

        $this->eventBus->addSubscriber($subscriber);

        return $this;
    }

    /**
     * Removes an event listener for the given event name.
     *
     * @param string   $eventName The event name.
     * @param callable $listener  The callback to remove.
     *
     * @return static The current instance.
     *
     * @see EventBus::removeListener()
     */
    public function removeEventListener($eventName, $listener)
    {
        if (!$this->eventBus) {
            $this->eventBus = EventBus::create();
        }

        $this->eventBus->removeListener($eventName, $listener);

        return $this;
    }

    /**
     * Removes an event subscriber from the eventBus.
     *
     * @param EventSubscriberInterface $subscriber The subscriber to remove.
     *
     * @return static The current instance.
     *
     * @see EventBus::removeSubscriber()
     */
    public function removeEventSubscriber(EventSubscriberInterface $subscriber)
    {
        if (!$this->eventBus) {
            $this->eventBus = EventBus::create();
        }

        $this->eventBus->removeSubscriber($subscriber);

        return $this;
    }
}
