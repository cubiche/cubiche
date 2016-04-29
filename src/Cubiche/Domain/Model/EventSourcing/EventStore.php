<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Core\Storage\MultidimensionalStorageInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * EventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStore
{
    /**
     * @var MultidimensionalStorageInterface
     */
    protected $storage;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * EventStore constructor.
     *
     * @param MultidimensionalStorageInterface $storage
     * @param SerializerInterface              $serializer
     */
    public function __construct(MultidimensionalStorageInterface $storage, SerializerInterface $serializer)
    {
        $this->storage = $storage;
        $this->serializer = $serializer;
    }

    /**
     * @param EventStream $eventStream
     */
    public function persist(EventStream $eventStream)
    {
        $key = $this->createKey($eventStream->className(), $eventStream->aggregateId());
        foreach ($eventStream->events() as $event) {
            $this->storage->push($key, $this->serializeEvent($event));
        }
    }

    /**
     * @param StringLiteral $className
     * @param IdInterface   $aggregateId
     *
     * @return EventStream
     */
    public function getEventsFor(StringLiteral $className, IdInterface $aggregateId)
    {
        $events = [];

        $key = $this->createKey($className, $aggregateId);
        foreach ($this->storage->getAll($key) as $data) {
            $events[] = $this->deserializeEvent($data);
        }

        return new EventStream($className, $aggregateId, $events);
    }

    /**
     * @param StringLiteral $className
     * @param IdInterface   $aggregateId
     *
     * @return string
     */
    protected function createKey(StringLiteral $className, IdInterface $aggregateId)
    {
        return sprintf('events:%s:%s', $className->toNative(), $aggregateId->toNative());
    }

    /**
     * @param EntityDomainEventInterface $event
     *
     * @return string
     */
    protected function serializeEvent(EntityDomainEventInterface $event)
    {
        $eventData = $this->serializer->serialize($event, 'json');

        return $this->serializer->serialize(
            array(
                'eventType' => get_class($event),
                'eventData' => $eventData,
            ),
            'json'
        );
    }

    /**
     * @param string $data
     *
     * @return EntityDomainEventInterface
     */
    protected function deserializeEvent($data)
    {
        $serializedEvent = $this->serializer->deserialize($data, 'array', 'json');

        return $this->serializer->deserialize(
            $serializedEvent['eventData'],
            $serializedEvent['eventType'],
            'json'
        );
    }
}
