<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Event;

use Cubiche\Domain\EventPublisher\DomainEvent;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;

/**
 * PreRemoveEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PreRemoveEvent extends DomainEvent
{
    /**
     * @var EventSourcedAggregateRootInterface
     */
    protected $aggregate;

    /**
     * PreRemoveEvent constructor.
     *
     * @param EventSourcedAggregateRootInterface $aggregate
     */
    public function __construct(EventSourcedAggregateRootInterface $aggregate)
    {
        parent::__construct();

        $this->aggregate = $aggregate;
    }

    /**
     * @return EventSourcedAggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }
}
