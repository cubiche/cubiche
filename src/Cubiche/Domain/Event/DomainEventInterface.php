<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Event;

use DateTime;
use Cubiche\Core\EventBus\EventInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * DomainEvent interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DomainEventInterface extends EventInterface
{
    /**
     * @param IdInterface $aggregateId
     */
    public function setAggregateId(IdInterface $aggregateId);

    /**
     * @return IdInterface
     */
    public function aggregateId();

    /**
     * @return DateTime
     */
    public function occurredOn();
}
