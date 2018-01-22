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

use Iterator;

/**
 * EventStream interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventStreamInterface extends Iterator
{
    /**
     * @return StreamName
     */
    public function streamName();

    /**
     * @return Iterator
     */
    public function events();
}
