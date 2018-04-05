<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasAccepted;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasCancelled;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasCommitted;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\ReservationWasRejected;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Event\SeatsAvailabilityWasCreated;
use Cubiche\Domain\System\Integer;

/**
 * SeatsAvailability class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailability extends AggregateRoot
{
    /**
     * @var ArrayHashMap
     */
    protected $reservations;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $availableSeats;

    /**
     * SeatsAvailability constructor.
     *
     * @param ConferenceId $conferenceId
     * @param int          $numberOfSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        Integer $numberOfSeats
    ) {
        parent::__construct($conferenceId);

        $this->recordAndApplyEvent(
            new SeatsAvailabilityWasCreated($conferenceId, $numberOfSeats)
        );
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->id;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function availableSeats()
    {
        return $this->availableSeats;
    }

    /**
     * @param ReservationId $reservationId
     * @param int           $numberOfSeats
     */
    public function makeReservation(ReservationId $reservationId, Integer $numberOfSeats)
    {
        if ($this->availableSeats->toNative() >= $numberOfSeats->toNative()) {
            $this->recordAndApplyEvent(
                new ReservationWasAccepted($this->conferenceId(), $reservationId, $numberOfSeats)
            );
        } else {
            $this->recordAndApplyEvent(
                new ReservationWasRejected($this->conferenceId(), $reservationId, $numberOfSeats)
            );
        }
    }

    /**
     * @param ReservationId $reservationId
     */
    public function cancelReservation(ReservationId $reservationId)
    {
        if (!$this->reservations->containsKey($reservationId->toNative())) {
            throw new \OutOfBoundsException('Unknown reservation: '.$reservationId->toNative());
        }

        $quantity = $this->reservations->get($reservationId->toNative());

        $this->recordAndApplyEvent(
            new ReservationWasCancelled($this->conferenceId(), $reservationId, $quantity)
        );
    }

    /**
     * @param ReservationId $reservationId
     */
    public function commitReservation(ReservationId $reservationId)
    {
        if (!$this->reservations->containsKey($reservationId->toNative())) {
            throw new \OutOfBoundsException('Unknown reservation: '.$reservationId->toNative());
        }

        $quantity = $this->reservations->get($reservationId->toNative());

        $this->recordAndApplyEvent(
            new ReservationWasCommitted($this->conferenceId(), $reservationId, $quantity)
        );
    }

    /**
     * @param SeatsAvailabilityWasCreated $event
     */
    protected function applySeatsAvailabilityWasCreated(SeatsAvailabilityWasCreated $event)
    {
        $this->availableSeats = $event->numberOfSeats();
        $this->reservations = new ArrayHashMap();
    }

    /**
     * @param ReservationWasAccepted $event
     */
    protected function applyReservationWasAccepted(ReservationWasAccepted $event)
    {
        $this->availableSeats = $this->availableSeats->sub($event->numberOfSeats());
        $this->reservations->set($event->reservationId()->toNative(), $event->numberOfSeats());
    }

    /**
     * @param ReservationWasRejected $event
     */
    protected function applyReservationWasRejected(ReservationWasRejected $event)
    {
    }

    /**
     * @param ReservationWasCancelled $event
     */
    protected function applyReservationWasCancelled(ReservationWasCancelled $event)
    {
        $this->availableSeats = $this->availableSeats->add($event->numberOfSeats());
        $this->reservations->removeAt($event->reservationId()->toNative());
    }

    /**
     * @param ReservationWasCommitted $event
     */
    protected function applyReservationWasCommitted(ReservationWasCommitted $event)
    {
        $this->reservations->removeAt($event->reservationId()->toNative());
    }
}
