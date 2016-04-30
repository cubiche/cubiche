<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

use Cubiche\Domain\Event\DomainEventPublisher;
use Cubiche\Domain\EventSourcing\EntityDomainEventInterface;
use Cubiche\Domain\Model\EventSourcing\EventStream;

/**
 * Abstract Aggregate Root Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AggregateRoot extends Entity implements AggregateRootInterface
{
    /**
     * @var EntityDomainEventInterface[]
     */
    private $recordedEvents = [];

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function applyAndPublishEvent(EntityDomainEventInterface $event)
    {
        $this->recordedEvents[] = $event;

        $this->applyEvent($event);
        $this->publishEvent($event);
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function applyEvent(EntityDomainEventInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        $method = 'apply'.end($classParts);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException(
                "There is no method named '$method' that can be applied to '".get_class($this)."'. "
            );
        }

        $this->$method($event);
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function publishEvent(EntityDomainEventInterface $event)
    {
        DomainEventPublisher::publish($event);
    }

    /**
     * @return EntityDomainEventInterface[]
     */
    public function recordedEvents()
    {
        return $this->recordedEvents;
    }

    /**
     * Clear recorded events.
     */
    public function clearEvents()
    {
        $this->recordedEvents = [];
    }

    /**
     * @param EventStream $history
     *
     * @return AggregateRootInterface
     */
    public static function loadFromHistory(EventStream $history)
    {
        if (static::class !== $history->className()->toNative()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'You cannot load an AggregateRoot of type %s from an EventStream for object of type %s',
                    static::class,
                    $history->className()->toNative()
                )
            );
        }

        $aggregateRoot = new static($history->aggregateId());
        foreach ($history->events() as $event) {
            $aggregateRoot->applyEvent($event);
        }

        return $aggregateRoot;
    }
}
