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
use Cubiche\Domain\EventSourcing\AggregateRootInterface;

/**
 * PrePersistEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PrePersistEvent extends DomainEvent
{
    /**
     * @var AggregateRootInterface
     */
    protected $aggregate;

    /**
     * PrePersistEvent constructor.
     *
     * @param AggregateRootInterface $aggregate
     */
    public function __construct(AggregateRootInterface $aggregate)
    {
        parent::__construct();

        $this->aggregate = $aggregate;
    }

    /**
     * @return AggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }
}
