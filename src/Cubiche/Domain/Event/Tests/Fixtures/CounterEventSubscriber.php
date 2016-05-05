<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Event\Tests\Fixtures;

use Cubiche\Domain\Event\DomainEventSubscriberInterface;

/**
 * CounterEventSubscriber class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CounterEventSubscriber implements DomainEventSubscriberInterface
{
    /**
     * @var int
     */
    protected $counter;

    /**
     * @return int
     */
    public function counter()
    {
        return $this->counter;
    }

    /**
     * @param IncrementCounterEvent $event
     */
    public function onIncrement(IncrementCounterEvent $event)
    {
        $this->counter += $event->step();
    }

    /**
     * @param DecrementCounterEvent $event
     */
    public function onDecrement(DecrementCounterEvent $event)
    {
        $this->counter -= $event->step();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            IncrementCounterEvent::class => 'onIncrement',
            DecrementCounterEvent::class => array('onDecrement', 21),
        );
    }
}
