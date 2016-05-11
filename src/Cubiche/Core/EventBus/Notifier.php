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

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Collections\SortedArrayCollection;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ReverseComparator;

/**
 * Notifier class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Notifier
{
    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var array
     */
    protected $sorted = array();

    /**
     * Notifier constructor.
     */
    public function __construct()
    {
        $this->listeners = new ArrayCollection();
    }

    /**
     * Emit an event to all registered listeners.
     *
     * @param $event
     *
     * @return EventInterface
     */
    public function notify($event)
    {
        $event = $this->ensureEvent($event);

        $eventName = $event->name();
        if ($listeners = $this->listeners($eventName)) {
            $this->doDispatch($listeners, $event);
        }

        return $event;
    }

    /**
     * Ensure event input is of type EventInterface or convert it.
     *
     * @param string|EventInterface $event
     *
     * @throws InvalidArgumentException
     *
     * @return EventInterface
     */
    public function ensureEvent($event)
    {
        if (is_string($event)) {
            return Event::named($event);
        }

        if (!$event instanceof EventInterface) {
            throw new \InvalidArgumentException(
                'Events should be provides as Event instances or string, received type: '.gettype($event)
            );
        }

        return $event;
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
        if (null !== $eventName) {
            if (!$this->listeners->containsKey($eventName)) {
                return array();
            }

            return $this->listeners->get($eventName);
        }

        return $this->listeners;
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
        if (!$this->listeners->containsKey($eventName)) {
            return;
        }

        /** @var SortedArrayCollection $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);

        /** @var ArrayCollection $listeners */
        foreach ($sortedListeners as $priority => $listeners) {
            foreach ($listeners as $registered) {
                /** @var DelegateListener $registered */
                if ($registered->equals($listener)) {
                    return $priority;
                }
            }
        }
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
        if ($eventName === null) {
            return $this->listeners->count() > 0;
        }

        return $this->listeners->containsKey($eventName);
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
        if (!$this->listeners->containsKey($eventName)) {
            $this->listeners->set($eventName, new SortedArrayCollection([], new ReverseComparator(new Comparator())));
        }

        /** @var SortedArrayCollection $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);
        if (!$sortedListeners->containsKey($priority)) {
            $sortedListeners->set($priority, new ArrayCollection());
        }

        $sortedListeners->get($priority)->add(new DelegateListener($listener));
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
        if (!$this->listeners->containsKey($eventName)) {
            return;
        }

        /** @var SortedArrayCollection $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);

        /** @var ArrayCollection $listeners */
        foreach ($sortedListeners as $priority => $listeners) {
            foreach ($listeners as $registered) {
                /** @var DelegateListener $registered */
                if ($registered->equals($listener)) {
                    $listeners->remove($registered);
                }
            }

            if ($listeners->count() == 0) {
                $sortedListeners->removeAt($priority);
            }
        }

        if ($sortedListeners->count() == 0) {
            $this->listeners->removeAt($eventName);
        }
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
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, array($subscriber, $params));
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, array($subscriber, $params[0]), isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener(
                        $eventName,
                        array($subscriber, $listener[0]),
                        isset($listener[1]) ? $listener[1] : 0
                    );
                }
            }
        }
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
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListener($eventName, array($subscriber, $listener[0]));
                }
            } else {
                $this->removeListener($eventName, array($subscriber, is_string($params) ? $params : $params[0]));
            }
        }
    }

    /**
     * Triggers the listeners of an event.
     *
     * @param SortedArrayCollection $sortedListeners
     * @param EventInterface        $event
     */
    protected function doDispatch(SortedArrayCollection $sortedListeners, EventInterface $event)
    {
        foreach ($sortedListeners as $priority => $listeners) {
            foreach ($listeners as $listener) {
                $listener($event);
                /** @var EventInterface $event */
                if ($event->isPropagationStopped()) {
                    break;
                }
            }
        }
    }
}
