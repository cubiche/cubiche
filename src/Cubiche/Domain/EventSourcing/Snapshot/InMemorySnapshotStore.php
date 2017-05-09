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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;

/**
 * InMemorySnapshotStore class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemorySnapshotStore implements SnapshotStoreInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $store;

    /**
     * InMemorySnapshot constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function persist(Snapshot $snapshot)
    {
        $this->store->set($snapshot->snapshotName(), $snapshot);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($snapshotName)
    {
        $this->store->removeAt($snapshotName);
    }

    /**
     * {@inheritdoc}
     */
    public function load($snapshotName)
    {
        return $this->store->get($snapshotName);
    }
}
