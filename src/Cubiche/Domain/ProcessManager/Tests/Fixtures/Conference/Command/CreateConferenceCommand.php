<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Conference\Command;

use Cubiche\Core\Bus\Command\Command;
use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CreateConferenceCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CreateConferenceCommand extends Command
{
    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $availableTickets;

    /**
     * CreateConferenceCommand constructor.
     *
     * @param string $conferenceId
     * @param string $name
     * @param int    $availableTickets
     */
    public function __construct(
        $conferenceId,
        $name,
        $availableTickets
    ) {
        $this->conferenceId = $conferenceId;
        $this->name = $name;
        $this->availableTickets = $availableTickets;
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
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function availableTickets()
    {
        return $this->availableTickets;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('conferenceId', Assertion::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('name', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint('availableTickets', Assertion::integer()->notBlank());
    }
}
