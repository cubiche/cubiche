<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\Model\Entity;

/**
 * Abstract aggregate root class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AggregateRoot extends Entity implements AggregateRootInterface
{
    /**
     * @var int
     */
    protected $version = 0;

    /**
     * @var DomainEventInterface[]
     */
    protected $recordedEvents = [];

    /**
     * @param DomainEventInterface $event
     */
    protected function recordAndApplyEvent(DomainEventInterface $event)
    {
        Validator::assert($event);

        $this->version += 1;
        $event->setVersion($this->version());

        $this->recordEvent($event);
        $this->applyEvent($event);
    }

    /**
     * @param DomainEventInterface $event
     */
    protected function applyEvent(DomainEventInterface $event)
    {
        $classParts = explode('\\', get_class($event));
        $method = 'apply'.end($classParts);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException(
                "There is no method named '$method' that can be applied to '".get_class($this)."'. "
            );
        }

        $this->$method($event);
        $this->setVersion($event->version());
    }

    /**
     * @param DomainEventInterface $event
     */
    protected function recordEvent(DomainEventInterface $event)
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return DomainEventInterface[]
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
        $reflector = new \ReflectionClass(static::class);

        /** @var AggregateRootInterface $aggregateRoot */
        $aggregateRoot = $reflector->newInstanceWithoutConstructor();
        $aggregateRoot->id = $history->streamName()->id();
        $aggregateRoot->replay($history);

        return $aggregateRoot;
    }

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history)
    {
        foreach ($history as $event) {
            $this->applyEvent($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
