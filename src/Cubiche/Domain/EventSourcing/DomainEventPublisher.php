<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\EventBus\Event\EventInterface;
use Cubiche\Core\EventBus\Event\EventSubscriberInterface;

/**
 * DomainEventPublisher class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEventPublisher
{
    /**
     * @var DomainEventPublisher
     */
    private static $instance = null;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * @return DomainEventPublisher
     */
    private static function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static();

            static::$instance->setEventBus(EventBus::create());
        }

        return static::$instance;
    }

    /**
     * @param EventBus $eventBus
     */
    public static function set(EventBus $eventBus)
    {
        static::instance()->setEventBus($eventBus);
    }

    /**
     * @return EventBus $eventBus
     */
    public static function eventBus()
    {
        return static::instance()->getEventBus();
    }

    /**
     * @param EventInterface $event
     */
    public static function publish(EventInterface $event)
    {
        static::instance()->dispatch($event);
    }

    /**
     * @param EventSubscriberInterface $subscriber
     */
    public static function subscribe(EventSubscriberInterface $subscriber)
    {
        static::instance()->addSubscriber($subscriber);
    }

    /**
     * @param EventInterface $event
     */
    protected function dispatch(EventInterface $event)
    {
        $this->eventBus->dispatch($event);
    }

    /**
     * @param EventSubscriberInterface $subscriber
     */
    protected function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventBus->addSubscriber($subscriber);
    }

    /**
     * @param EventBus $eventBus
     */
    protected function setEventBus(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @return EventBus
     */
    protected function getEventBus()
    {
        return $this->eventBus;
    }
}
