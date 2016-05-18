<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

use Cubiche\Domain\Model\EventSourcing\EntityDomainEventInterface;
use Cubiche\Domain\Model\EventSourcing\EventStream;

/**
 * Aggregate Root Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface AggregateRootInterface extends EntityInterface
{
    /**
     * @return EntityDomainEventInterface[]
     */
    public function recordedEvents();

    /**
     * Clear recorded events.
     */
    public function clearEvents();

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history);
}
