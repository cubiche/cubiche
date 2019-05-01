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

use Cubiche\Core\Bus\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CommitSeatReservationCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommitSeatReservationCommand extends Command
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
     * CommitSeatReservationCommand constructor.
     *
     * @param string $conferenceId
     * @param string $reservationId
     */
    public function __construct(
        $conferenceId,
        $reservationId
    ) {
        $this->conferenceId = $conferenceId;
        $this->reservationId = $reservationId;
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
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('reservationId', Assertion::uuid()->notBlank());
    }
}
