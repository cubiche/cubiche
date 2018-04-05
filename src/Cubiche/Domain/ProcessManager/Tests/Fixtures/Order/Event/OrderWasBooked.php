<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderId;

/**
 * OrderWasBooked class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderWasBooked extends DomainEvent
{
    /**
     * OrderWasBooked constructor.
     *
     * @param OrderId $orderId
     */
    public function __construct(OrderId $orderId)
    {
        parent::__construct($orderId);
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->aggregateId();
    }
}
