<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\ReservationId;
use Cubiche\Domain\System\Integer;

/**
 * ReservationWasAccepted class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ReservationWasAccepted extends DomainEvent
{
    /**
     * @var ReservationId
     */
    protected $reservationId;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfSeats;

    /**
     * ReservationWasAccepted constructor.
     *
     * @param ConferenceId  $conferenceId
     * @param ReservationId $reservationId
     * @param int           $numberOfSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        ReservationId $reservationId,
        Integer $numberOfSeats
    ) {
        parent::__construct($conferenceId);

        $this->reservationId = $reservationId;
        $this->numberOfSeats = $numberOfSeats;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->aggregateId();
    }

    /**
     * @return ReservationId
     */
    public function reservationId()
    {
        return $this->reservationId;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }
}
