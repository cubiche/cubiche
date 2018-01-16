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

use Cubiche\Domain\EventSourcing\Snapshot\SnapshotInterface;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Infrastructure\MongoDB\Common\Connection;
use MongoDB\Collection;
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
     * @var Collection
     */
    protected $collection;

    /**
     * MongoDBSnapshotStore constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->database = new Database($this->connection->manager(), $this->connection->database());
        $this->collection = $this->database->selectCollection('snapshots');
    }

    /**
     * {@inheritdoc}
     */
    public function persist(SnapshotInterface $snapshot)
    {
        $this->collection->findOneAndUpdate(
            array('_id' => $snapshot->id()->toNative()),
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
    public function load(IdInterface $id)
    {
        $document = $this->collection->findOne(array(
            '_id' => $id->toNative(),
        ));

        if ($document !== null) {
            return unserialize($document['payload']);
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(IdInterface $id)
    {
        $this->collection->deleteMany(array(
            '_id' => $id->toNative(),
        ));
    }
}
