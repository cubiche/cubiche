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
     * @var string
     */
    protected $aggregateName;

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
        $this->aggregateName = $this->aggregateName();
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCreateSnapshot(EventSourcedAggregateRootInterface $eventSourcedAggregateRoot)
    {
        $recordedEvents = $eventSourcedAggregateRoot->recordedEvents();

        if (count($recordedEvents) > 0) {
            $lastSnapshot = $this->loadSnapshot($eventSourcedAggregateRoot->id());
            $threshold = new \DateTimeImmutable(date('c', strtotime('-'.$this->threshold)));

            if ($lastSnapshot !== null && $lastSnapshot->createdAt() > $threshold) {
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
        $version = VersionManager::versionOfClass($this->aggregateClassName);

        return $this->snapshotStore->load($this->aggregateName, $id, $version);
    }

    /**
     * @return string
     */
    public function aggregateName()
    {
        $pieces = explode(' ', trim(preg_replace('([A-Z])', ' $0', $this->shortClassName())));

        return strtolower(implode('_', $pieces));
    }

    /**
     * @return string
     */
    protected function shortClassName()
    {
        // If class name has a namespace separator, only take last portion
        if (strpos($this->aggregateClassName, '\\') !== false) {
            return substr($this->aggregateClassName, strrpos($this->aggregateClassName, '\\') + 1);
        }

        return $this->aggregateClassName;
    }
}
