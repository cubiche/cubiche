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

use Cubiche\Domain\EventPublisher\DomainEvent as BaseDomainEvent;
use Cubiche\Domain\Model\IdInterface;

/**
 * DomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEvent extends BaseDomainEvent implements DomainEventInterface
{
    /**
     * EntityDomainEvent constructor.
     *
     * @param IdInterface $aggregateId
     */
    public function __construct(IdInterface $aggregateId)
    {
        parent::__construct();

        $this->setMetadata('aggregateId', $aggregateId);
        $this->setMetadata('eventId', DomainEventId::next());
        $this->setVersion(0);
    }

    /**
     * {@inheritdoc}
     */
    public function eventId()
    {
        return $this->getMetadata('eventId');
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->getMetadata('aggregateId');
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this->getMetadata('version');
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->setMetadata('version', $version);
    }
}
