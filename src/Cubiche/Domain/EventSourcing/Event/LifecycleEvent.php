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

use Cubiche\Core\Bus\NamedMessageInterface;
use Cubiche\Core\EventBus\Event\Event;
use Cubiche\Domain\EventSourcing\AggregateRootInterface;

/**
 * LifecycleEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class LifecycleEvent extends Event implements NamedMessageInterface
{
    /**
     * @var AggregateRootInterface
     */
    protected $aggregate;

    /**
     * LifecycleEvent constructor.
     *
     * @param AggregateRootInterface $aggregate
     */
    public function __construct(AggregateRootInterface $aggregate)
    {
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
