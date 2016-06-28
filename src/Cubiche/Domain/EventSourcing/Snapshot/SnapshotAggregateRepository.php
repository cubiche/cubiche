<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Snapshot;

use Cubiche\Domain\EventSourcing\Aggregate\Versioning\VersionManager;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRepository;
use Cubiche\Domain\Model\IdInterface;

/**
 * SnapshotAggregateRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SnapshotAggregateRepository extends EventSourcedAggregateRepository
{
    /**
     * @var SnapshotStoreInterface
     */
    protected $snapshotStore;

    /**
     * AggregateRepository constructor.
     *
     * @param EventStoreInterface    $eventStore
     * @param SnapshotStoreInterface $snapshotStore
     * @param string                 $aggregateClassName
     */
    public function __construct(
        EventStoreInterface $eventStore,
        SnapshotStoreInterface $snapshotStore,
        $aggregateClassName
    ) {
        parent::__construct($eventStore, $aggregateClassName);

        $this->snapshotStore = $snapshotStore;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        $version = VersionManager::versionOfClass($this->aggregateClassName);
        $snapshot = $this->snapshotStore->load($this->aggregateName, $id, $version);
        $version = $snapshot->version();

        if ($snapshot !== null) {
            $eventStream = $this->eventStore->load($this->aggregateName, $id, $version);

            $eventSourcedAggregateRoot = $snapshot->aggregate();

            $eventSourcedAggregateRoot->setVersion($version);
            $eventSourcedAggregateRoot->replay($eventStream);

            return $eventSourcedAggregateRoot;
        }

        return parent::get($id);
    }
}
