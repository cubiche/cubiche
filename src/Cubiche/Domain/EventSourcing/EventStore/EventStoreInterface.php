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
     * @param EventStreamInterface $eventStream
     *
     * @return int
     */
    public function persist(EventStreamInterface $eventStream);

    /**
     * @param StreamName $streamName
     *
     * @return mixed
     */
    public function remove(StreamName $streamName);

    /**
     * @param StreamName $streamName
     * @param int        $version
     *
     * @return EventStreamInterface|null
     */
    public function load(StreamName $streamName, $version = 0);
}
