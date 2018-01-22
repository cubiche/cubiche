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

use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Policy\SnapshottingPolicyInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * SnapshotAggregateRepository class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SnapshotAggregateRepository extends AggregateRepository
{
    /**
     * @var SnapshotStoreInterface
     */
    protected $snapshotStore;

    /**
     * @var SnapshottingPolicyInterface
     */
    protected $snapshottingPolicy;

    /**
     * AggregateRepository constructor.
     *
     * @param EventStoreInterface         $eventStore
     * @param SnapshotStoreInterface      $snapshotStore
     * @param SnapshottingPolicyInterface $snapshottingPolicy
     * @param string                      $aggregateClassName
     */
    public function __construct(
        EventStoreInterface $eventStore,
        SnapshotStoreInterface $snapshotStore,
        SnapshottingPolicyInterface $snapshottingPolicy,
        $aggregateClassName
    ) {
        parent::__construct($eventStore, $aggregateClassName);

        $this->snapshotStore = $snapshotStore;
        $this->snapshottingPolicy = $snapshottingPolicy;
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdInterface $id)
    {
        $snapshot = $this->loadSnapshot($id);
        if ($snapshot !== null) {
            return $this->snapshotToAggregateRoot($snapshot);
        }

        return parent::get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(AggregateRootInterface $element)
    {
        if ($this->snapshottingPolicy->shouldCreateSnapshot($element)) {
            $this->saveSnapshot($element);
        }

        parent::persist($element);
    }

    /**
     * Load a aggregate snapshot from the storage.
     *
     * @param IdInterface $id
     *
     * @return Snapshot|null
     */
    protected function loadSnapshot(IdInterface $id)
    {
        return $this->snapshotStore->load($id);
    }

    /**
     * Save the aggregate snapshot.
     *
     * @param AggregateRootInterface $aggregateRoot
     */
    protected function saveSnapshot(AggregateRootInterface $aggregateRoot)
    {
        $snapshot = new Snapshot($aggregateRoot->id(), $aggregateRoot);

        $this->snapshotStore->persist($snapshot);
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return AggregateRootInterface
     */
    protected function snapshotToAggregateRoot(Snapshot $snapshot)
    {
        $history = $this->eventStore->load(
            $this->streamName($snapshot->id()),
            $snapshot->version()
        );

        $aggregateRoot = $snapshot->aggregate();

        $aggregateRoot->setVersion($snapshot->version());
        $aggregateRoot->replay($history);

        return $aggregateRoot;
    }
}
