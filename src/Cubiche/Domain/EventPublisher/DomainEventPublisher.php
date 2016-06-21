<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventPublisher;

use Cubiche\Core\Bus\Event\EventBus;

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
     * @param DomainEventInterface $event
     */
    public static function publish(DomainEventInterface $event)
    {
        static::instance()->dispatch($event);
    }

    /**
     * @param DomainEventSubscriberInterface $subscriber
     */
    public static function subscribe(DomainEventSubscriberInterface $subscriber)
    {
        static::instance()->addSubscriber($subscriber);
    }

    /**
     * @param DomainEventInterface $event
     */
    protected function dispatch(DomainEventInterface $event)
    {
        $this->eventBus->dispatch($event);
    }

    /**
     * @param DomainEventSubscriberInterface $subscriber
     */
    protected function addSubscriber(DomainEventSubscriberInterface $subscriber)
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
