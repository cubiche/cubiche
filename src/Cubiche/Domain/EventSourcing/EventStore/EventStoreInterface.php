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

use Cubiche\Domain\EventSourcing\Aggregate\Versioning\Version;
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
     * @param Version     $version
     */
    public function persist(EventStream $eventStream, Version $version);

    /**
     * @param string      $streamName
     * @param IdInterface $aggregateId
     * @param Version     $version
     *
     * @return EventStream
     */
    public function load($streamName, IdInterface $aggregateId, Version $version);

    /**
     * @param string      $streamName
     * @param IdInterface $aggregateId
     * @param Version     $version
     */
    public function remove($streamName, IdInterface $aggregateId, Version $version);
}
