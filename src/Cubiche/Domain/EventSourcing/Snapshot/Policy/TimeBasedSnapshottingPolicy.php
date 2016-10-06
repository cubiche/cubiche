<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Snapshot\Policy;

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Snapshot;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;
use Cubiche\Domain\EventSourcing\Utils\NameResolver;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
use Cubiche\Domain\Model\IdInterface;

/**
 * TimeBasedSnapshottingPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TimeBasedSnapshottingPolicy implements SnapshottingPolicyInterface
{
    /**
     * @var SnapshotStoreInterface
     */
    protected $snapshotStore;

    /**
     * @var string
     */
    protected $threshold;

    /**
     * @var string
     */
    protected $aggregateClassName;

    /**
     * TimeBasedSnapshottingPolicy constructor.
     *
     * @param SnapshotStoreInterface $snapshotStore
     * @param string                 $aggregateClassName
     * @param string                 $threshold
     */
    public function __construct(SnapshotStoreInterface $snapshotStore, $aggregateClassName, $threshold = '1 hour')
    {
        $this->snapshotStore = $snapshotStore;
        $this->threshold = $threshold;

        $this->aggregateClassName = $aggregateClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCreateSnapshot(EventSourcedAggregateRootInterface $eventSourcedAggregateRoot)
    {
        $recordedEvents = $eventSourcedAggregateRoot->recordedEvents();

        if (count($recordedEvents) > 0) {
            $lastSnapshot = $this->loadSnapshot($eventSourcedAggregateRoot->id());
            $threshold = new \DateTime(date('c', strtotime('-'.$this->threshold)));

            if ($lastSnapshot !== null && $lastSnapshot->createdAt() < $threshold) {
                return true;
            }
        }

        return false;
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
        $applicationVersion = VersionManager::currentApplicationVersion();
        $aggregateVersion = VersionManager::versionOfClass($this->aggregateClassName, $applicationVersion);

        return $this->snapshotStore->load($this->streamName(), $id, $aggregateVersion, $applicationVersion);
    }

    /**
     * @return string
     */
    protected function streamName()
    {
        return NameResolver::resolve($this->aggregateClassName);
    }
}
