<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\Cqrs\WriteModelInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\Model\AggregateRootInterface;

/**
 * EventSourcedAggregateRoot interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventSourcedAggregateRootInterface extends AggregateRootInterface, WriteModelInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public function recordedEvents();

    /**
     * Clear recorded events.
     */
    public function clearEvents();

    /**
     * @param EventStream $history
     *
     * @return AggregateRootInterface
     */
    public static function loadFromHistory(EventStream $history);

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history);

    /**
     * @return int
     */
    public function version();

    /**
     * @param int $version
     */
    public function setVersion($version);
}
