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
use Cubiche\Domain\Model\IdInterface;

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
     * @var int
     */
    protected $versionBase;

    /**
     * EventStore constructor.
     *
     * @param MultidimensionalStorageInterface $storage
     * @param SerializerInterface              $serializer
     * @param int                              $versionBase
     */
    public function __construct(
        MultidimensionalStorageInterface $storage,
        SerializerInterface $serializer,
        $versionBase
    ) {
        $this->storage = $storage;
        $this->serializer = $serializer;

        if ((int) $versionBase <= 0) {
            throw new \InvalidArgumentException('The version base should be greater than cero.');
        }

        $this->versionBase = (int) $versionBase;
    }

    /**
     * @param EventStream $eventStream
     */
    public function persist(EventStream $eventStream)
    {
        $key = $this->createKey(
            $eventStream->className(),
            $eventStream->aggregateId()
        );

        foreach ($eventStream->events() as $event) {
            $this->storage->push($key, $this->serializeEvent($event));
        }
    }

    /**
     * @param string      $className
     * @param int         $version
     * @param IdInterface $aggregateId
     *
     * @return EventStream
     */
    public function eventsFor($className, $version, IdInterface $aggregateId)
    {
        $key = $this->createKey(
            $className,
            $aggregateId
        );

        $events = [];
        foreach ($this->storage->slice($key, $version * $this->versionBase) as $data) {
            $events[] = $this->deserializeEvent($data);
        }

        return new EventStream($className, $aggregateId, $events);
    }

    /**
     * @param string      $className
     * @param IdInterface $aggregateId
     *
     * @return int
     */
    public function versionFor($className, IdInterface $aggregateId)
    {
        $countOfEvents = $this->countEventsFor($className, $aggregateId);

        return intval($countOfEvents / $this->versionBase);
    }

    /**
     * @param string      $className
     * @param IdInterface $aggregateId
     *
     * @return int
     */
    protected function countEventsFor($className, IdInterface $aggregateId)
    {
        $key = $this->createKey(
            $className,
            $aggregateId
        );

        return $this->storage->count($key);
    }

    /**
     * @param string      $className
     * @param IdInterface $aggregateId
     *
     * @return string
     */
    protected function createKey($className, IdInterface $aggregateId)
    {
        $classParts = explode('\\', $className);

        return sprintf(
            'events:%s:%s',
            strtolower(end($classParts)),
            $aggregateId->toNative()
        );
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
