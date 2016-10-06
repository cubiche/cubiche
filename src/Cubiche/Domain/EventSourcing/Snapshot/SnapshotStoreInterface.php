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

use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\Model\IdInterface;

/**
 * SnapshotStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SnapshotStoreInterface
{
    /**
     * @param Snapshot $snapshot
     * @param Version  $applicationVersion
     *
     * @return
     */
    public function persist(Snapshot $snapshot, Version $applicationVersion);

    /**
     * @param string      $aggregateType
     * @param IdInterface $aggregateId
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     *
     * @return Snapshot|null
     */
    public function load(
        $aggregateType,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    );

    /**
     * @param string      $aggregateType
     * @param IdInterface $aggregateId
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     *
     * @return
     */
    public function remove(
        $aggregateType,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    );
}
