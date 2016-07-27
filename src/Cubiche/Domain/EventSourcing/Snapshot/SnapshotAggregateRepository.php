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

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRepository;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Policy\SnapshottingPolicyInterface;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
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
    public function persist($element)
    {
        if (!$element instanceof EventSourcedAggregateRootInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                EventSourcedAggregateRootInterface::class,
                is_object($element) ? get_class($element) : gettype($element)
            ));
        }

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
     * @return Snapshot
     */
    protected function loadSnapshot(IdInterface $id)
    {
        $version = VersionManager::versionOfClass($this->aggregateClassName);

        return $this->snapshotStore->load($this->streamName(), $id, $version);
    }

    /**
     * Save the aggregate snapshot.
     *
     * @param EventSourcedAggregateRootInterface $aggregateRoot
     */
    protected function saveSnapshot(EventSourcedAggregateRootInterface $aggregateRoot)
    {
        $snapshot = new Snapshot($this->streamName(), $aggregateRoot, new \DateTime());

        $this->snapshotStore->persist($snapshot);
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return EventSourcedAggregateRootInterface
     */
    protected function snapshotToAggregateRoot(Snapshot $snapshot)
    {
        $history = $this->eventStore->load($this->streamName(), $snapshot->aggregateId(), $snapshot->version());

        $aggregateRoot = $snapshot->aggregate();

        $aggregateRoot->setVersion($snapshot->version());
        $aggregateRoot->replay($history);

        return $aggregateRoot;
    }
}
