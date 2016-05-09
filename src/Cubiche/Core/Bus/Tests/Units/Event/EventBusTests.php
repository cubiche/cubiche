<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Event;

use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\Event\LoginUserEvent;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * EventBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventBusTests extends TestCase
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
                ->exception(function () use ($eventBus) {
                    $eventBus->dispatch(new LoginUserEvent('ivan@cubiche.com'));
                })
                ->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test dispatch chained middlewares.
     */
    public function testDispatchChainedMiddlewares()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->and($event = new LoginUserEvent('info@cubiche.org'))
            ->and($eventBus->addListener($event->name(), function (LoginUserEvent $event) {
                $this
                    ->string($event->email())
                    ->isEqualTo('info@cubiche.org')
                ;

                $event->setEmail('fake@email.com');
            }))
            ->and($eventBus->dispatch($event))
            ->then()
                ->string($event->email())
                    ->isEqualTo('fake@email.com')
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
