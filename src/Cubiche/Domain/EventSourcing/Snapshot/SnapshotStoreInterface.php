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

/**
 * SnapshotStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SnapshotStoreInterface
{
    /**
     * @param Snapshot $snapshot
     */
    public function persist(Snapshot $snapshot);

    /**
     * @param string $snapshotName
     */
    public function remove($snapshotName);

    /**
     * @param string $snapshotName
     *
     * @return Snapshot|null
     */
    public function load($snapshotName);
}
