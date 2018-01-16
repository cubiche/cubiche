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

use Cubiche\Domain\Model\IdInterface;

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
     * @param IdInterface $id
     *
     * @return mixed
     */
    public function remove(IdInterface $id);

    /**
     * @param IdInterface $id
     * @param int         $version
     *
     * @return EventStreamInterface|null
     */
    public function load(IdInterface $id, $version = 0);
}
