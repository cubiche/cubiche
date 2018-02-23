<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager;

use Cubiche\Domain\EventPublisher\DomainEventSubscriberInterface;
use Cubiche\Domain\ProcessManager\ProcessManager;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Event\ConferenceWasCreated;
use Cubiche\Domain\ProcessManager\Tests\Fixtures\SeatsAvailability\Command\CreateSeatsAvailabilityCommand;

/**
 * SimpleProcessManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SimpleProcessManager extends ProcessManager implements DomainEventSubscriberInterface
{
    /**
     * @param ConferenceWasCreated $event
     */
    public function whenConferenceWasCreated(ConferenceWasCreated $event)
    {
        $this->dispatch(new CreateSeatsAvailabilityCommand(
            $event->conferenceId()->toNative(),
            $event->availableTickets()->toNative()
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function name()
    {
        return 'app.process_manager.simple';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ConferenceWasCreated::class => array('whenConferenceWasCreated', 250),
        );
    }
}
