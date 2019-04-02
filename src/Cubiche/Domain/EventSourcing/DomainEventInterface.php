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

use Cubiche\Core\EventBus\Event\EventInterface;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\DateTime\DateTime;

/**
 * DomainEvent interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DomainEventInterface extends EventInterface
{
    /**
     * @return IdInterface
     */
    public function aggregateId(): IdInterface;

    /**
     * @return int
     */
    public function version(): int;

    /**
     * @param int $version
     */
    public function setVersion(int $version);

    /**
     * @return DateTime
     */
    public function occurredOn(): DateTime;
}
