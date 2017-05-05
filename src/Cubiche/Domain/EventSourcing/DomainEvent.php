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
     * @var DomainEventId
     */
    protected $eventId;

    /**
     * EntityDomainEvent constructor.
     *
     * @param IdInterface $aggregateId
     */
    public function __construct(IdInterface $aggregateId)
    {
        parent::__construct();

        $this->setMetadata('aggregateId', $aggregateId);
        $this->setVersion(0);
        $this->eventId = DomainEventId::next();
    }

    /**
     * {@inheritdoc}
     */
    public function eventId()
    {
        return $this->eventId;
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

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        /** @var DomainEvent $domainEvent */
        $domainEvent = parent::fromArray($data);
        $domainEvent->eventId = $data['eventId'];

        return $domainEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), array(
            'eventId' => $this->eventId(),
        ));
    }
}
