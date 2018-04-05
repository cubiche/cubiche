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
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\OrderId;
use Cubiche\Domain\System\Integer;

/**
 * OrderWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderWasCreated extends DomainEvent
{
    /**
     * @var ConferenceId
     */
    protected $conferenceId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfTickets;

    /**
     * OrderWasCreated constructor.
     *
     * @param OrderId      $orderId
     * @param ConferenceId $conferenceId
     * @param int          $numberOfTickets
     */
    public function __construct(
        OrderId $orderId,
        ConferenceId $conferenceId,
        Integer $numberOfTickets
    ) {
        parent::__construct($orderId);

        $this->conferenceId = $conferenceId;
        $this->numberOfTickets = $numberOfTickets;
    }

    /**
     * @return OrderId
     */
    public function orderId()
    {
        return $this->aggregateId();
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfTickets()
    {
        return $this->numberOfTickets;
    }
}
