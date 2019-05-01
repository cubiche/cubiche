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

use Cubiche\Core\Bus\Message\Resolver\MessageNameResolverInterface;
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
     * @var MessageNameResolverInterface
     */
    protected $messageNameResolver;

    /**
     * @var ArrayHashMap
     */
    protected $listeners;

    /**
     * EventDispatcher constructor.
     */
    public function __construct(
        MessageNameResolverInterface $messageNameResolver,
        EventSubscriberInterface ...$eventSubscribers
    ) {
        $this->messageNameResolver = $messageNameResolver;
        $this->listeners = new ArrayHashMap();

        foreach ($eventSubscribers as $eventSubscriber) {
            $this->addSubscriber($eventSubscriber);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(EventInterface $event)
    {
        // pre dispatch event
        $preDispatchEvent = new PreDispatchEvent($event);
        $eventName = $this->messageNameResolver->resolve($preDispatchEvent);
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $preDispatchEvent);
        }

        // dispatch event
        $eventName = $this->messageNameResolver->resolve($event);
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $event);
        }

        // post dispatch event
        $postDispatchEvent = new PostDispatchEvent($event);
        $eventName = $this->messageNameResolver->resolve($postDispatchEvent);
        if ($listeners = $this->eventListeners($eventName)) {
            $this->doDispatch($listeners, $postDispatchEvent);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function eventListeners(string $eventName): array
    {
        if (!$this->listeners->containsKey($eventName)) {
            return array();
        }

        return $this->listeners->get($eventName)->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function listenerPriority(string $eventName, callable $listener): ?int
    {
        if (!$this->listeners->containsKey($eventName)) {
            return null;
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

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasEventListeners(string $eventName): bool
    {
        return $this->listeners->containsKey($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners(): bool
    {
        return $this->listeners->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0)
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
    public function removeListener(string $eventName, callable $listener)
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
     * @param array $sortedListeners
     * @param EventInterface     $event
     */
    protected function doDispatch(array $sortedListeners, EventInterface $event)
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
