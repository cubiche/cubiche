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

use Cubiche\Core\Bus\Event\Event;
use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\EventDispatcher\EventDispatcherMiddleware;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\Event\LoginUserEvent;
use Cubiche\Core\Bus\Tests\Fixtures\Event\LoginUserEventListener;
use Cubiche\Core\Bus\Tests\Fixtures\Event\UserEventSubscriber;
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
            ->and($eventBus->addListener($event->eventName(), function (LoginUserEvent $event) {
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

    /**
     * Test dispatcherMiddleware method.
     */
    public function testDispatcherMiddleware()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->when($middleware = $eventBus->dispatcherMiddleware())
            ->then()
                ->object($middleware)
                    ->isInstanceOf(EventDispatcherMiddleware::class)
        ;
    }

    /**
     * Test AddListener method.
     */
    public function testAddListener()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->and($eventBus->addListener('event.foo', array(new LoginUserEventListener(), 'onLogin')))
            ->and($eventBus->addListener('event.foo', function (Event $event) {

            }))
            ->and($eventBus->addListener('event.bar', function (Event $event) {

            }))
            ->when($listeners = $eventBus->listeners())
            ->then()
                ->array($listeners->toArray())
                    ->hasKey('event.foo')
                    ->hasKey('event.bar')
                ->array($listeners->toArray())
                    ->hasSize(2)
                ->array($listeners->toArray())
                    ->array['event.foo']
                        ->hasSize(2)
        ;
    }

    /**
     * Test RemoveListener method.
     */
    public function testRemoveListener()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->and($listener1 = array(new LoginUserEventListener(), 'onLogin'))
            ->and($listener2 = function (Event $event) {
                return $event->eventName();
            })
            ->and($listener3 = function (Event $event) {

            })
            ->and($eventBus->addListener('event.foo', $listener1, 100))
            ->and($eventBus->addListener('event.foo', $listener2, 50))
            ->and($eventBus->addListener('event.bar', $listener3))
            ->then()
                ->boolean($eventBus->hasEventListeners('event.foo'))
                    ->isTrue()
                ->and()
                ->when($eventBus->removeListener('event.foo', $listener1))
                ->then()
                    ->boolean($eventBus->hasEventListeners('event.foo'))
                        ->isTrue()
                ->and()
                ->when($eventBus->removeListener('event.unknow', $listener2))
                ->and($eventBus->removeListener('event.foo', $listener2))
                ->then()
                    ->boolean($eventBus->hasEventListeners('event.foo'))
                        ->isFalse()
        ;
    }

    /**
     * Test AddSubscriber method.
     */
    public function testAddSubscriber()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->and($eventBus->addSubscriber(new UserEventSubscriber()))
            ->then()
                ->boolean($eventBus->hasListeners())
                    ->isTrue()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::FOO_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::BAR_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::USER_LOGIN))
                    ->isTrue()
        ;
    }

    /**
     * Test RemoveSubscriber method.
     */
    public function testRemoveSubscriber()
    {
        $this
            ->given($eventBus = EventBus::create())
            ->and($subscriber = new UserEventSubscriber())
            ->and($eventBus->addSubscriber($subscriber))
            ->then()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::FOO_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::BAR_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasEventListeners(UserEventSubscriber::USER_LOGIN))
                    ->isTrue()
                ->and()
                ->when($eventBus->removeSubscriber($subscriber))
                ->then()
                    ->boolean($eventBus->hasEventListeners(UserEventSubscriber::FOO_EVENT))
                        ->isFalse()
                    ->boolean($eventBus->hasEventListeners(UserEventSubscriber::BAR_EVENT))
                        ->isFalse()
                    ->boolean($eventBus->hasEventListeners(UserEventSubscriber::USER_LOGIN))
                        ->isFalse()
        ;
    }
}
