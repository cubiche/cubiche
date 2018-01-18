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
use ArrayIterator;

/**
 * EventStream class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStream implements EventStreamInterface
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**
     * @var DomainEventInterface[]
     */
    protected $events = [];

    /**
     * EventStream constructor.
     *
     * @param IdInterface            $id
     * @param DomainEventInterface[] $events
     */
    public function __construct(IdInterface $id, array $events)
    {
        $this->id = $id;
        $this->events = new ArrayIterator();
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
     * @return IdInterface
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param IdInterface $id
     *
     * @return mixed
     */
    public function setId(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @return Iterator
     */
    public function events()
    {
        return $this->events;
    }

    /**
     * @return DomainEventInterface
     */
    public function current()
    {
        return $this->events->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->events->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->events->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->events->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->events->rewind();
    }
}
