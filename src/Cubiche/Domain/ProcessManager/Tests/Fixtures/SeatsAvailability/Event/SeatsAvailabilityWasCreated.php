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
use Cubiche\Domain\System\Integer;

/**
 * SeatsAvailabilityWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SeatsAvailabilityWasCreated extends DomainEvent
{
    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $numberOfSeats;

    /**
     * SeatsAvailabilityWasCreated constructor.
     *
     * @param ConferenceId $conferenceId
     * @param int          $numberOfSeats
     */
    public function __construct(
        ConferenceId $conferenceId,
        Integer $numberOfSeats
    ) {
        parent::__construct($conferenceId);

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
     * @return \Cubiche\Domain\System\Integer
     */
    public function numberOfSeats()
    {
        return $this->numberOfSeats;
    }
}
