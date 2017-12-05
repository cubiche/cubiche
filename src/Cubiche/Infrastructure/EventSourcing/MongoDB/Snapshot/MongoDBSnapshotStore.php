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

use Cubiche\Domain\EventSourcing\Snapshot\Snapshot;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use MongoDB\Database;

/**
 * MongoDBSnapshotStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBSnapshotStore implements SnapshotStoreInterface
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
     * MongoDBSnapshotStore constructor.
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
    public function persist(Snapshot $snapshot)
    {
        $collection = $this->getCollection($snapshot->snapshotName());
        $collection->findOneAndUpdate(
            array('_id' => $snapshot->aggregate()->id()->toNative()),
            array(
                '$set' => array(
                    'payload' => serialize($snapshot),
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
            return unserialize($document['payload']);
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

        $collection->deleteMany(array(
            '_id' => $aggregateId,
        ));
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

    /**
     * Returns the Collection instance for a class.
     *
     * @param string $snapshotName
     *
     * @return \MongoDB\Collection
     */
    public function getCollection($snapshotName)
    {
        $collectionName = $this->snapshotNameToCollectionName($snapshotName);
        if (!isset($this->collections[$collectionName])) {
            $collection = $this->database->selectCollection($collectionName);

            $this->collections[$collectionName] = $collection;
        }

        return $this->collections[$collectionName];
    }
}
