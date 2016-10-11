<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\EventStore;

use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\Model\IdInterface;

/**
 * EventStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventStoreInterface
{
    /**
     * @param EventStream $eventStream
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     *
     * @return
     */
    public function persist(EventStream $eventStream, Version $aggregateVersion, Version $applicationVersion);

    /**
     * @param string      $streamName
     * @param IdInterface $aggregateId
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     *
     * @return EventStream
     */
    public function load(
        $streamName,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    );

    /**
     * @param string      $streamName
     * @param IdInterface $aggregateId
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     */
    public function remove(
        $streamName,
        IdInterface $aggregateId,
        Version $aggregateVersion,
        Version $applicationVersion
    );

    /**
     * @param string  $streamName
     * @param Version $aggregateVersion
     * @param Version $applicationVersion
     *
     * @return EventStream
     */
    public function loadAll($streamName, Version $aggregateVersion, Version $applicationVersion);

    /**
     * @param string  $streamName
     * @param Version $aggregateVersion
     * @param Version $applicationVersion
     */
    public function removeAll($streamName, Version $aggregateVersion, Version $applicationVersion);
}
