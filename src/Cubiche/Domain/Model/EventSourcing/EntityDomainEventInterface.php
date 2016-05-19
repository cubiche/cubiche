<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Domain\EventPublisher\DomainEventInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * EntityDomainEvent interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EntityDomainEventInterface extends DomainEventInterface
{
    /**
     * @return IdInterface
     */
    public function aggregateId();
}
