<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventSourcing\MongoDB\Snapshot;

use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Snapshot;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;

/**
 * MongoDBSnapshotStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBSnapshotStore implements SnapshotStoreInterface
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
     * MongoDBSnapshotStore constructor.
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
    public function persist(Snapshot $snapshot)
    {
        $collection = $this->getCollection($snapshot->snapshotName());
        $collection->update(
            array('_id' => $snapshot->aggregate()->id()->toNative()),
            array(
                '$set' => array(
                    'payload' => $this->serializer->serialize($snapshot),
                ),
            ),
            array('upsert' => true)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load($snapshotName)
    {
        $collection = $this->getCollection($snapshotName);
        $aggregateId = $this->snapshotNameToAggregareId($snapshotName);

        $document = $collection->findOne(array(
            '_id' => $aggregateId,
        ));

        if ($document !== null) {
            return $this->serializer->deserialize($document['payload']);
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($snapshotName)
    {
        $collection = $this->getCollection($snapshotName);
        $aggregateId = $this->snapshotNameToAggregareId($snapshotName);

        $collection->remove(array(
            '_id' => $aggregateId,
        ));
    }

    /**
     * Get mongo db stream collection.
     *
     * @param string $snapshotName
     *
     * @return \MongoCollection
     */
    private function getCollection($snapshotName)
    {
        $collection = $this->mongoClient->selectCollection(
            $this->databaseName,
            $this->snapshotNameToCollectionName($snapshotName)
        );

        $collection->setReadPreference(\MongoClient::RP_PRIMARY);

        return $collection;
    }

    /**
     * @param string $snapshotName
     *
     * @return string
     */
    private function snapshotNameToCollectionName($snapshotName)
    {
        $snapshotName = substr($snapshotName, 0, strpos($snapshotName, '-'));
        $pieces = explode(' ', trim(preg_replace('([A-Z])', ' $0', $snapshotName)));

        return strtolower(implode('_', $pieces)).'_snapshot';
    }

    /**
     * @param string $snapshotName
     *
     * @return string
     */
    private function snapshotNameToAggregareId($snapshotName)
    {
        return substr($snapshotName, strpos($snapshotName, '-') + 1);
    }
}
