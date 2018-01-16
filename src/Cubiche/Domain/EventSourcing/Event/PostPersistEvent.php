<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Event;

use Cubiche\Domain\EventPublisher\DomainEvent;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStreamInterface;

/**
 * PostPersistEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostPersistEvent extends DomainEvent
{
    /**
     * @var EventSourcedAggregateRootInterface
     */
    protected $aggregate;

    /**
     * @var EventStream
     */
    protected $eventStream;

    /**
     * PostPersistEvent constructor.
     *
     * @param EventSourcedAggregateRootInterface $aggregate
     * @param EventStreamInterface               $eventStream
     */
    public function __construct(EventSourcedAggregateRootInterface $aggregate, EventStreamInterface $eventStream)
    {
        parent::__construct();

        $this->aggregate = $aggregate;
        $this->eventStream = $eventStream;
    }

    /**
     * @return EventSourcedAggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }

    /**
     * @return EventStreamInterface
     */
    public function eventStream()
    {
        return $this->eventStream;
    }
}
