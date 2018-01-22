<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventSourcing\MongoDB\EventStore;

use Cubiche\Domain\EventSourcing\DomainEventInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\EventStore\EventStreamInterface;
use Cubiche\Domain\EventSourcing\EventStore\StreamName;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use MongoDB\Database;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\WriteConcern;

/**
 * MongoDBEventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBEventStore implements EventStoreInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var array
     */
    protected $collections;

    /**
     * MongoDBEventStore constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->database = new Database($this->connection->manager(), $this->connection->database());
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EventStreamInterface $eventStream)
    {
        $collection = $this->getCollection($eventStream->streamName()->name()->toNative());

        $version = 0;
        if (count($eventStream->events()) > 0) {
            $bulkWrite = new BulkWrite(['ordered' => true]);
            /** @var DomainEventInterface $event */
            foreach ($eventStream->events() as $event) {
                $version = $event->version();
                $bulkWrite->update(
                    array(
                        '_id' => $event->id()->toNative(),
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
    public function load(StreamName $streamName, $version = 0)
    {
        $collection = $this->getCollection($streamName->name()->toNative());
        $query = array();

        if ($version > 0) {
            $query = array(
                'version' => array('$gte' => $version),
            );
        }

        $documents = $collection
            ->find($query, array('sort' => array('version' => 1)))
        ;

        $domainEvents = [];
        foreach ($documents as $document) {
            $domainEvents[] = $this->arrayToEvent($document);
        }

        if (count($domainEvents) > 0) {
            return new EventStream($streamName, $domainEvents);
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(StreamName $streamName)
    {
        $collection = $this->getCollection($streamName->name()->toNative());
        $collection->drop();
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
     * Returns the Collection instance for a class.
     *
     * @param string $collectionName
     *
     * @return \MongoDB\Collection
     */
    public function getCollection($collectionName)
    {
        if (!isset($this->collections[$collectionName])) {
            $collection = $this->database->selectCollection($collectionName);
            $collection->createIndex(array(
                'version' => 1,
            ), array(
                'unique' => true,
            ));

            $this->collections[$collectionName] = $collection;
        }

        return $this->collections[$collectionName];
    }
}
