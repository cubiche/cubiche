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
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\SortedArrayHashMap;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ReverseComparator;

/**
 * EventDispatcher class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $listeners;

    /**
     * EventDispatcher constructor.
     */
    public function __construct()
    {
        $this->listeners = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($event)
    {
        $event = $this->ensureEvent($event);

        // pre dispatch event
        $preDispatchEvent = new PreDispatchEvent($event);
        $eventName = $preDispatchEvent->eventName();
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $preDispatchEvent);
        }

        // dispatch event
        $eventName = $event->eventName();
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $event);
        }

        // post dispatch event
        $postDispatchEvent = new PostDispatchEvent($event);
        $eventName = $postDispatchEvent->eventName();
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $postDispatchEvent);
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
                sprintf(
                    'Events should be provides as %s instances or string, received type: %s',
                    EventInterface::class,
                    gettype($event)
                )
            );
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function eventListeners($eventName)
    {
        if (!$this->listeners->containsKey($eventName)) {
            return array();
        }

        return $this->listeners->get($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function listeners()
    {
        return $this->listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function listenerPriority($eventName, callable $listener)
    {
        if (!$this->listeners->containsKey($eventName)) {
            return;
        }

        /** @var SortedArrayHashMap $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);

        /** @var ArrayList $listeners */
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
     * {@inheritdoc}
     */
    public function hasEventListeners($eventName)
    {
        return $this->listeners->containsKey($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners()
    {
        return $this->listeners->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, callable $listener, $priority = 0)
    {
        if (!$this->listeners->containsKey($eventName)) {
            $this->listeners->set($eventName, new SortedArrayHashMap([], new ReverseComparator(new Comparator())));
        }

        /** @var SortedArrayHashMap $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);
        if (!$sortedListeners->containsKey($priority)) {
            $sortedListeners->set($priority, new ArrayList());
        }

        $sortedListeners->get($priority)->add(new DelegateListener($listener));
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($eventName, callable $listener)
    {
        if (!$this->listeners->containsKey($eventName)) {
            return;
        }

        /** @var SortedArrayHashMap $sortedListeners */
        $sortedListeners = $this->listeners->get($eventName);

        /** @var ArrayList $listeners */
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @param SortedArrayHashMap $sortedListeners
     * @param EventInterface     $event
     */
    protected function doDispatch(SortedArrayHashMap $sortedListeners, EventInterface $event)
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
