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
use Cubiche\Domain\Model\IdInterface;

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
    public function persist(SnapshotInterface $snapshot)
    {
        $this->store->set($snapshot->id()->toNative(), $snapshot);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(IdInterface $id)
    {
        $this->store->removeAt($id->toNative());
    }

    /**
     * {@inheritdoc}
     */
    public function load(IdInterface $id)
    {
        return $this->store->get($id->toNative());
    }
}
