<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventPublisher;

use Cubiche\Core\Bus\Event\EventInterface;
use DateTime;

/**
 * DomainEvent interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DomainEventInterface extends EventInterface
{
    /**
     * @return DateTime
     */
    public function occurredOn();
}
