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

use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Domain\EventSourcing\DomainEventInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;

/**
 * MongoDBEventStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBEventStore implements EventStoreInterface
{
    /**
     * @var \MongoClient
     */
    protected $mongoClient;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Mongo DB write concern
     * The default options can be overridden with the constructor.
     *
     * @var array
     */
    protected $writeConcern = [
        'w' => 1,
    ];

    /**
     * MongoDBEventStore constructor.
     *
     * @param \MongoClient        $mongoClient
     * @param string              $databaseName
     * @param SerializerInterface $serializer
     */
    public function __construct(\MongoClient $mongoClient, $databaseName, SerializerInterface $serializer)
    {
        $this->mongoClient = $mongoClient;
        $this->databaseName = $databaseName;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EventStream $eventStream)
    {
        $version = 0;
        if (count($eventStream->events()) > 0) {
            $this->ensureIndex($eventStream->streamName());
            $batch = $this->getInsertBatch($eventStream->streamName());

            foreach ($eventStream->events() as $event) {
                $version = $event->version();
                $batch->add($this->eventToArray($event));
            }

            try {
                $batch->execute();
            } catch (\MongoWriteConcernException $e) {
                $code = $e->getDocument()['writeErrors'][0]['code'];
                if (in_array($code, [11000, 11001, 12582])) {
                    throw new \Exception('At least one event with same version exists already', 0, $e);
                }
            }
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function load($streamName, $version = 0)
    {
        $collection = $this->getCollection($streamName);
        $aggregateId = $this->streamNameToAggregareId($streamName);

        $query = array(
            'aggregate_id' => $aggregateId,
        );

        if ($version > 0) {
            $query['version'] = array(
                '$gte' => $version,
            );
        }

        $documents = $collection
            ->find($query)
            ->sort(array('version' => $collection::ASCENDING))
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
    public function remove($streamName)
    {
        $collection = $this->getCollection($streamName);
        $aggregateId = $this->streamNameToAggregareId($streamName);

        $collection->remove(array(
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
        $eventData = $event->toArray();

        return array(
            '_id' => $event->eventId()->toNative(),
            'aggregate_id' => $event->aggregateId()->toNative(),
            'event_type' => $event->eventName(),
            'version' => $event->version(),
            'payload' => $this->serializer->serialize($eventData['payload']),
            'metadata' => $this->serializer->serialize($eventData['metadata']),
            'created_at' => $event->occurredOn()->format('Y-m-d\TH:i:s.u'),
        );
    }

    /**
     * @param array $eventData
     *
     * @return DomainEventInterface
     */
    private function arrayToEvent(array $eventData)
    {
        return call_user_func(
            array($eventData['event_type'], 'fromArray'),
            array(
                'payload' => $this->serializer->deserialize($eventData['payload']),
                'metadata' => $this->serializer->deserialize($eventData['metadata']),
            )
        );
    }

    /**
     * Get mongo db insert batch.
     *
     * @param string $streamName
     *
     * @return \MongoInsertBatch
     */
    private function getInsertBatch($streamName)
    {
        return new \MongoInsertBatch($this->getCollection($streamName), $this->writeConcern);
    }

    /**
     * @param string $streamName
     */
    private function ensureIndex($streamName)
    {
        $collection = $this->getCollection($streamName);
        $collection->createIndex(array(
            'aggregate_id' => 1,
            'version' => 1,
        ), array(
            'unique' => true,
        ));

        $collection->createIndex(array(
            'created_at' => 1,
            'version' => 1,
        ));
    }

    /**
     * Get mongo db stream collection.
     *
     * @param string $streamName
     *
     * @return \MongoCollection
     */
    private function getCollection($streamName)
    {
        $collection = $this->mongoClient->selectCollection(
            $this->databaseName,
            $this->streamNameToCollectionName($streamName)
        );

        $collection->setReadPreference(\MongoClient::RP_PRIMARY);

        return $collection;
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
}
