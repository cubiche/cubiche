<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventBus\Tests\Units\Event;

use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;
use Cubiche\Core\Bus\Tests\Units\BusTests;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\EventBus\Tests\Fixtures\Event\LoginUserEvent;
use Cubiche\Core\EventBus\Tests\Fixtures\Event\UserEventSubscriber;

/**
 * EventBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBusTests extends BusTests
{
    /**
     * Test create without event dispatcher middleware.
     */
    public function testCreateWithoutEventDispatcherMiddleware()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($eventBus = new EventBus([$middleware]))
            ->then()
                // test that nothing happens. No exception is raised.
                ->variable($eventBus->dispatch(new LoginUserEvent('ivan@cubiche.com')))
                    ->isNull()
        ;
    }

    /**
     * Test dispatch chained middlewares.
     */
    public function testDispatchChainedMiddlewares()
    {
        $this
            ->given($eventBus = EventBus::create(new UserEventSubscriber()))
            ->and($event = new LoginUserEvent('info@cubiche.org'))
            ->and($eventBus->dispatch($event))
            ->then()
                ->string($event->email())
                    ->isEqualTo('success@cubiche.org')
        ;
    }

    /**
     * Test dispatch with invalid event.
     */
    public function testDispatchWithInvalidEvent()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->then()
                ->exception(function () use ($eventBus) {
                    $eventBus->dispatch(new FooMessage());
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
