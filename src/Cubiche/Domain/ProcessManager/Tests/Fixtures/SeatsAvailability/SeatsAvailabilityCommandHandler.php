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

use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CancelSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CommitSeatReservationCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CreateSeatsAvailabilityCommand;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\MakeSeatReservationCommand;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\Integer;

/**
 * SeatsAvailabilityCommandHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * SeatsAvailabilityCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateSeatsAvailabilityCommand $command
     */
    public function createSeatsAvailability(CreateSeatsAvailabilityCommand $command)
    {
        $seatsAvailability = new SeatsAvailability(
            ConferenceId::fromNative($command->conferenceId()),
            Integer::fromNative($command->numberOfSeats())
        );

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param MakeSeatReservationCommand $command
     */
    public function makeSeatReservation(MakeSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());

        $seatsAvailability->makeReservation(
            ReservationId::fromNative($command->reservationId()),
            Integer::fromNative($command->numberOfSeats())
        );

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param CancelSeatReservationCommand $command
     */
    public function cancelSeatReservation(CancelSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());
        $seatsAvailability->cancelReservation(ReservationId::fromNative($command->reservationId()));

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param CommitSeatReservationCommand $command
     */
    public function commitSeatReservation(CommitSeatReservationCommand $command)
    {
        $seatsAvailability = $this->findOr404($command->conferenceId());
        $seatsAvailability->commitReservation(ReservationId::fromNative($command->reservationId()));

        $this->repository->persist($seatsAvailability);
    }

    /**
     * @param string $conferenceId
     *
     * @return SeatsAvailability
     */
    private function findOr404($conferenceId)
    {
        $seatsAvailability = $this->repository->get(ConferenceId::fromNative($conferenceId));
        if ($seatsAvailability === null) {
            throw new \RuntimeException(sprintf(
                'There is no seatsAvailability with id: %s',
                $conferenceId
            ));
        }

        return $seatsAvailability;
    }
}
