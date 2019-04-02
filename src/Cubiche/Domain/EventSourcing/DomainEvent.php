<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\EventBus\Event\Event;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\DateTime\DateTime;

/**
 * DomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEvent extends Event implements DomainEventInterface
{
    /**
     * @var int
     */
    protected $version;

    /**
     * @var DateTime
     */
    protected $occurredOn;

    /**
     * DomainEvent constructor.
     *
     * @param IdInterface $aggregateId
     * @param string|null $messageName
     */
    public function __construct(IdInterface $aggregateId, string $messageName = null)
    {
        parent::__construct($aggregateId->toNative(), $messageName);

        $this->version = 0;
        $this->occurredOn = DateTime::now();
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId(): IdInterface
    {
        return $this->messageId();
    }

    /**
     * {@inheritdoc}
     */
    public function version(): int
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }

    /**
     * @return DateTime
     */
    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @param DateTime $occurredOn
     */
    public function setOccurredOn(DateTime $occurredOn)
    {
        $this->occurredOn = $occurredOn;
    }
}
