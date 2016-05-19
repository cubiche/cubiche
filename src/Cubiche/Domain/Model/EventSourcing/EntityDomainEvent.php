<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Domain\EventPublisher\DomainEvent;
use Cubiche\Domain\Model\IdInterface;

/**
 * EntityDomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EntityDomainEvent extends DomainEvent implements EntityDomainEventInterface
{
    /**
     * @var IdInterface
     */
    protected $aggregateId;

    /**
     * EntityDomainEvent constructor.
     *
     * @param IdInterface $aggregateId
     */
    public function __construct(IdInterface $aggregateId)
    {
        parent::__construct();

        $this->aggregateId = $aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }
}
