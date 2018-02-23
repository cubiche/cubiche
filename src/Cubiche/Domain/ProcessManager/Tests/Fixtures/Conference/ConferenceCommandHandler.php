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

use Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Command\CreateConferenceCommand;
use Cubiche\Domain\Repository\RepositoryInterface;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\StringLiteral;

/**
 * ConferenceCommandHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConferenceCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * ConferenceCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateConferenceCommand $command
     */
    public function createConference(CreateConferenceCommand $command)
    {
        $conference = new Conference(
            ConferenceId::fromNative($command->conferenceId()),
            StringLiteral::fromNative($command->name()),
            Integer::fromNative($command->availableTickets())
        );

        $this->repository->persist($conference);
    }
}
