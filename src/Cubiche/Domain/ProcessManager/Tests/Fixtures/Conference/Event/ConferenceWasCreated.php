<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Event;

use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\ConferenceId;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;

/**
 * ConferenceWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceWasCreated extends DomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @var int
     */
    protected $availableTickets;

    /**
     * ConferenceWasCreated constructor.
     *
     * @param ConferenceId  $conferenceId
     * @param StringLiteral $name
     * @param int           $availableTickets
     */
    public function __construct(
        ConferenceId $conferenceId,
        StringLiteral $name,
        Integer $availableTickets
    ) {
        parent::__construct($conferenceId);

        $this->name = $name;
        $this->availableTickets = $availableTickets;
    }

    /**
     * @return ConferenceId
     */
    public function conferenceId()
    {
        return $this->aggregateId();
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return \Cubiche\Domain\System\Integer
     */
    public function availableTickets()
    {
        return $this->availableTickets;
    }
}
