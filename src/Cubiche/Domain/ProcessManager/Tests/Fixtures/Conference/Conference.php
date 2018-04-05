<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference;

use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Event\ConferenceWasCreated;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;

/**
 * Conference class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Conference extends AggregateRoot
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @var \Cubiche\Domain\System\Integer
     */
    protected $availableTickets;

    /**
     * Conference constructor.
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

        $this->recordAndApplyEvent(
            new ConferenceWasCreated($conferenceId, $name, $availableTickets)
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

    /**
     * @param ConferenceWasCreated $event
     */
    protected function applyConferenceWasCreated(ConferenceWasCreated $event)
    {
        $this->name = $event->name();
        $this->availableTickets = $event->availableTickets();
    }
}
