<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * MakeSeatReservationCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MakeSeatReservationCommand extends Command
{
    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var string
     */
    protected $reservationId;

    /**
     * @var int
     */
    protected $numberOfSeats;

    /**
     * MakeSeatReservationCommand constructor.
     *
     * @param string $conferenceId
     * @param string $reservationId
     * @param int    $numberOfSeats
     */
    public function __construct(
        $conferenceId,
        $reservationId,
        $numberOfSeats
    ) {
        $this->conferenceId = $conferenceId;
        $this->reservationId = $reservationId;
        $this->numberOfSeats = $numberOfSeats;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return string
     */
    public function reservationId()
    {
        return $this->reservationId;
    }

    /**
     * @return int
     */
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('reservationId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('numberOfSeats', Assertion::integer()->notBlank());
    }
}
