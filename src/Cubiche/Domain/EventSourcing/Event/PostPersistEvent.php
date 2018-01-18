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
use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStreamInterface;

/**
 * PostPersistEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostPersistEvent extends DomainEvent
{
    /**
     * @var AggregateRootInterface
     */
    protected $aggregate;

    /**
     * @var EventStream
     */
    protected $eventStream;

    /**
     * PostPersistEvent constructor.
     *
     * @param AggregateRootInterface $aggregate
     * @param EventStreamInterface   $eventStream
     */
    public function __construct(AggregateRootInterface $aggregate, EventStreamInterface $eventStream)
    {
        parent::__construct();

        $this->aggregate = $aggregate;
        $this->eventStream = $eventStream;
    }

    /**
     * @return AggregateRootInterface
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
