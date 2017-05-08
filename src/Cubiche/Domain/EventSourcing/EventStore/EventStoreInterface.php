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

/**
 * EventStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventStoreInterface
{
    /**
     * @param EventStream $eventStream
     *
     * @return int
     */
    public function persist(EventStream $eventStream);

    /**
     * @param string $streamName
     */
    public function remove($streamName);

    /**
     * @param string $streamName
     * @param int    $version
     *
     * @return EventStream|null
     */
    public function load($streamName, $version = 0);
}
