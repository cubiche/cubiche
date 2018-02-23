<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Order\Command;

use Cubiche\Core\Cqrs\Command\Command;
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * CreateOrderCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CreateOrderCommand extends Command
{
    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $conferenceId;

    /**
     * @var int
     */
    protected $numberOfTickets;

    /**
     * CreateOrderCommand constructor.
     *
     * @param string $orderId
     * @param string $conferenceId
     * @param int    $numberOfTickets
     */
    public function __construct(
        $orderId,
        $conferenceId,
        $numberOfTickets
    ) {
        $this->orderId = $orderId;
        $this->conferenceId = $conferenceId;
        $this->numberOfTickets = $numberOfTickets;
    }

    /**
     * @return string
     */
    public function orderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function conferenceId()
    {
        return $this->conferenceId;
    }

    /**
     * @return int
     */
    public function numberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('orderId', Assert::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('conferenceId', Assert::uuid()->notBlank());
        $classMetadata->addPropertyConstraint('numberOfTickets', Assert::intType()->notBlank());
    }
}
