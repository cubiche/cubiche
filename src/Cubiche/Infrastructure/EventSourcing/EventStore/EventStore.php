<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventStore;

use Cubiche\Domain\EventSourcing\DomainEventInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\EventStore\EventStreamInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use MongoDB\Database;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\WriteConcern;

/**
 * EventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStore implements EventStoreInterface
{
    /**
     * EventStore constructor.
     *
     * @param Connection $connection
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EventStreamInterface $eventStream)
    {
        $streamName = $eventStream->streamName();
        $expectedVersion = $eventStream->version();

        if (count($eventStream->events()) > 0) {
            $bulkWrite = new BulkWrite(['ordered' => true]);

            foreach ($eventStream->events() as $event) {
                $version = $event->version();
                $bulkWrite->update(
                    array(
                        '_id' => $event->id()->toNative(),
                        'aggregate_id' => $eventStream->id()->toNative(),
                        'version' => $event->version(),
                    ),
                    array('$set' => $this->eventToArray($event)),
                    array('upsert' => true)
                );
            }

            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);

            $this->database->getManager()->executeBulkWrite(
                $collection->getNamespace(),
                $bulkWrite,
                $writeConcern
            );
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function load(IdInterface $id, $version = 0)
    {
        $collection = $this->getCollection($streamName);
        $aggregateId = $this->streamNameToAggregareId($streamName);

        if ($version > 0) {
            $query = array(
                'aggregate_id' => $aggregateId,
                'version' => array('$gte' => $version),
            );
        } else {
            $query = array(
                'aggregate_id' => $aggregateId,
            );
        }

        $documents = $collection
            ->find($query, array('sort' => array('events.version' => 1)))
        ;

        $domainEvents = [];
        foreach ($documents as $document) {
            $domainEvents[] = $this->arrayToEvent($document);
        }

        if (count($domainEvents) > 0) {
            return new EventStream(
                $streamName,
                $domainEvents[0]->aggregateId(),
                $domainEvents
            );
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(IdInterface $id)
    {
        $collection = $this->getCollection($streamName);
        $aggregateId = $this->streamNameToAggregareId($streamName);

        $collection->deleteMany(array(
            'aggregate_id' => $aggregateId,
        ));
    }

    /**
     * @param DomainEventInterface $event
     *
     * @return array
     */
    private function eventToArray(DomainEventInterface $event)
    {
        return array(
            'eventType' => $event->eventName(),
            'payload' => serialize($event),
        );
    }

    /**
     * @param array $eventData
     *
     * @return DomainEventInterface
     */
    private function arrayToEvent($eventData)
    {
        $eventData = json_decode(
            json_encode($eventData),
            true
        );

        return unserialize($eventData['payload']);
    }

    /**
     * @param string $streamName
     *
     * @return string
     */
    private function streamNameToCollectionName($streamName)
    {
        $streamName = substr($streamName, 0, strpos($streamName, '-'));
        $pieces = explode(' ', trim(preg_replace('([A-Z])', ' $0', $streamName)));

        return strtolower(implode('_', $pieces)).'_stream';
    }

    /**
     * @param string $streamName
     *
     * @return string
     */
    private function streamNameToAggregareId($streamName)
    {
        return substr($streamName, strpos($streamName, '-') + 1);
    }

    /**
     * Returns the Collection instance for a class.
     *
     * @param string $streamName
     *
     * @return \MongoDB\Collection
     */
    public function getCollection($streamName)
    {
        $collectionName = $this->streamNameToCollectionName($streamName);
        if (!isset($this->collections[$collectionName])) {
            $collection = $this->database->selectCollection($collectionName);
            $collection->createIndex(array(
                'aggregate_id' => 1,
                'version' => 1,
            ), array(
                'unique' => true,
            ));

            $this->collections[$collectionName] = $collection;
        }

        return $this->collections[$collectionName];
    }
}
