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

use Cubiche\Domain\Model\IdInterface;

/**
 * SnapshotStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SnapshotStoreInterface
{
    /**
     * @param SnapshotInterface $snapshot
     */
    public function persist(SnapshotInterface $snapshot);

    /**
     * @param IdInterface $id
     */
    public function remove(IdInterface $id);

    /**
     * @param IdInterface $id
     *
     * @return SnapshotInterface|null
     */
    public function load(IdInterface $id);
}
