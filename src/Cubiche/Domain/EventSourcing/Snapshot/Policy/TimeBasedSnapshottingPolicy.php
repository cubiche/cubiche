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

use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Snapshot;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;
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
    public function shouldCreateSnapshot(AggregateRootInterface $aggregateRoot)
    {
        $recordedEvents = $aggregateRoot->recordedEvents();

        if (count($recordedEvents) > 0) {
            $lastSnapshot = $this->loadSnapshot($aggregateRoot->id());
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
     * @return Snapshot|null
     */
    protected function loadSnapshot(IdInterface $id)
    {
        return $this->snapshotStore->load($id);
    }
}
