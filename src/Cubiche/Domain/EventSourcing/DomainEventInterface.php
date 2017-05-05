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

use Cubiche\Domain\EventPublisher\DomainEventInterface as BaseDomainEventInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * DomainEvent interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DomainEventInterface extends BaseDomainEventInterface
{
    /**
     * @return DomainEventId
     */
    public function eventId();

    /**
     * @return IdInterface
     */
    public function aggregateId();

    /**
     * @return int
     */
    public function version();

    /**
     * @param int $version
     */
    public function setVersion($version);
}
