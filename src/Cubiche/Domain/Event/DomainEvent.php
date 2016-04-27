<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Event;

use DateTime;
use Cubiche\Core\EventBus\Event;
use Cubiche\Domain\Model\IdInterface;

/**
 * DomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEvent extends Event implements DomainEventInterface
{
    /**
     * @var IdInterface
     */
    protected $aggregateId;

    /**
     * @var IdInterface
     */
    protected $occurredOn;

    /**
     * DomainEvent constructor.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->occurredOn = new DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function setAggregateId(IdInterface $aggregateId)
    {
        $this->aggregateId = $aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
