<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\EventStore;

use Cubiche\Domain\EventSourcing\DomainEventInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * EventStream class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStream
{
    /**
     * @var string
     */
    protected $streamName;

    /**
     * @var IdInterface
     */
    protected $aggregateId;

    /**
     * @var DomainEventInterface[]
     */
    protected $events = [];

    /**
     * EntityDomainEvent constructor.
     *
     * @param string                 $streamName
     * @param IdInterface            $aggregateId
     * @param DomainEventInterface[] $events
     */
    public function __construct($streamName, IdInterface $aggregateId, array $events)
    {
        $this->streamName = $streamName;
        $this->aggregateId = $aggregateId;

        foreach ($events as $event) {
            if (!$event instanceof DomainEventInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The event must be an instance of %s. Instance of %s given',
                        DomainEventInterface::class,
                        is_object($event) ? get_class($event) : gettype($event)
                    )
                );
            }

            $this->events[] = $event;
        }
    }

    /**
     * @return string
     */
    public function streamName()
    {
        return $this->streamName;
    }

    /**
     * @return IdInterface
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return DomainEventInterface[]
     */
    public function events()
    {
        return $this->events;
    }
}
