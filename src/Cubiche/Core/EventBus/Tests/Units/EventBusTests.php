<?php

/**
 * This file is part of the Cubiche/EventBus component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventBus\Tests\Units;

use Cubiche\Core\EventBus\Event;
use Cubiche\Core\EventBus\EventBus;
use Cubiche\Core\EventBus\Exception\InvalidMiddlewareException;
use Cubiche\Core\EventBus\Exception\NotFoundException;
use Cubiche\Core\EventBus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\EventBus\Middlewares\Notifier\NotifierMiddleware;
use Cubiche\Core\EventBus\Notifier;
use Cubiche\Core\EventBus\Tests\Fixtures\EncoderMiddleware;
use Cubiche\Core\EventBus\Tests\Fixtures\LoginUserEvent;
use Cubiche\Core\EventBus\Tests\Fixtures\LoginUserEventListener;
use Cubiche\Core\EventBus\Tests\Fixtures\UserEventSubscriber;

/**
 * EventBus class.
 *
 * Generated by TestGenerator on 2016-04-11 at 15:18:25.
 */
class EventBusTests extends TestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->then()
                ->object($eventBus)
                    ->isInstanceOf(EventBus::class)
        ;
    }

    /**
     * Test create with invalid middleware.
     */
    public function testCreateWithInvalidMiddleware()
    {
        $this
            ->given($lockingMiddleware = new LockingMiddleware())
            ->given($notifierMiddleware = new NotifierMiddleware(new Notifier()))
            ->then()
                ->exception(function () use ($lockingMiddleware) {
                    new EventBus([$lockingMiddleware]);
                })
                ->isInstanceOf(NotFoundException::class)
                ->exception(function () {
                    new EventBus([]);
                })
                ->isInstanceOf(NotFoundException::class)
                ->exception(function () use ($notifierMiddleware) {
                    new EventBus([$notifierMiddleware, new \StdClass()]);
                })
                ->isInstanceOf(InvalidMiddlewareException::class)
        ;
    }

    /**
     * Test notify chained middlewares.
     */
    public function testNotifyChainedMiddlewares()
    {
        $this
            ->given($notifier = new Notifier())
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->and($notifier->addListener($event->name(), function (LoginUserEvent $event) {
                $this
                    ->string($event->email())
                    ->isEqualTo(md5('ivan@cubiche.com'))
                ;

                $event->setEmail('fake@email.com');
            }))
            ->and($encoderMiddleware = new EncoderMiddleware('md5'))
            ->and($notifierMiddleware = new NotifierMiddleware($notifier))
            ->and($eventBus = new EventBus([$encoderMiddleware, $notifierMiddleware]))
            ->when($eventBus->notify($event))
            ->then()
                ->string($event->email())
                    ->isEqualTo('fake@email.com')
        ;

        $this
            ->given($notifier = new Notifier())
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->and($notifier->addListener($event->name(), function (LoginUserEvent $event) {
                $this
                    ->string($event->email())
                    ->isEqualTo(sha1(sha1('ivan@cubiche.com')))
                ;

                $event->setEmail('fake@email.com');
            }))
            ->and($encoderMiddleware = new EncoderMiddleware('sha1'))
            ->and($notifierMiddleware = new NotifierMiddleware($notifier))
            ->and($eventBus = new EventBus([$encoderMiddleware, $encoderMiddleware, $notifierMiddleware]))
            ->when($eventBus->notify($event))
            ->then()
                ->string($event->email())
                    ->isEqualTo('fake@email.com')
        ;
    }

    /**
     * Test AddListener method.
     */
    public function testAddListener()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
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
     * Test listenerPriority method.
     */
    public function testListenerPriority()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->and($listener1 = array(new LoginUserEventListener(), 'onLogin'))
            ->and($listener2 = function (Event $event) {
                return $event->name();
            })
            ->and($listener3 = function (Event $event) {

            })
            ->and($eventBus->addListener('event.foo', $listener1, 100))
            ->and($eventBus->addListener('event.foo', $listener2, 50))
            ->and($eventBus->addListener('event.bar', $listener3))
            ->then()
                ->variable($eventBus->listenerPriority('event.unknow', $listener1))
                    ->isNull()
                ->variable($eventBus->listenerPriority('event.foo', $listener3))
                    ->isNull()
                ->integer($eventBus->listenerPriority('event.foo', $listener1))
                    ->isEqualTo(100)
                ->integer($eventBus->listenerPriority('event.foo', $listener2))
                    ->isEqualTo(50)
        ;
    }

    /**
     * Test HasListeners method.
     */
    public function testHasListeners()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->and($listener1 = array(new LoginUserEventListener(), 'onLogin'))
            ->and($listener2 = function (Event $event) {
                return $event->name();
            })
            ->and($listener3 = function (Event $event) {

            })
            ->and($eventBus->addListener('event.foo', $listener1, 100))
            ->and($eventBus->addListener('event.foo', $listener2, 50))
            ->and($eventBus->addListener('event.bar', $listener3))
            ->then()
                ->boolean($eventBus->hasListeners('event.unknow'))
                    ->isFalse()
                ->boolean($eventBus->hasListeners('event.foo'))
                    ->isTrue()
                ->boolean($eventBus->hasListeners())
                    ->isTrue()
        ;
    }

    /**
     * Test RemoveListener method.
     */
    public function testRemoveListener()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->and($listener1 = array(new LoginUserEventListener(), 'onLogin'))
            ->and($listener2 = function (Event $event) {
                return $event->name();
            })
            ->and($listener3 = function (Event $event) {

            })
            ->and($eventBus->addListener('event.foo', $listener1, 100))
            ->and($eventBus->addListener('event.foo', $listener2, 50))
            ->and($eventBus->addListener('event.bar', $listener3))
            ->then()
                ->boolean($eventBus->hasListeners('event.foo'))
                    ->isTrue()
                ->and()
                ->when($eventBus->removeListener('event.foo', $listener1))
                ->then()
                    ->boolean($eventBus->hasListeners('event.foo'))
                        ->isTrue()
                    ->and()
                    ->when($eventBus->removeListener('event.unknow', $listener2))
                    ->when($eventBus->removeListener('event.foo', $listener2))
                    ->then()
                        ->boolean($eventBus->hasListeners('event.foo'))
                            ->isFalse()
        ;
    }

    /**
     * Test AddSubscriber method.
     */
    public function testAddSubscriber()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->and($eventBus->addSubscriber(new UserEventSubscriber()))
            ->then()
                ->boolean($eventBus->hasListeners())
                    ->isTrue()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::FOO_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::BAR_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::USER_LOGIN))
                    ->isTrue()
        ;
    }

    /**
     * Test RemoveSubscriber method.
     */
    public function testRemoveSubscriber()
    {
        $this
            ->given($eventBus = new EventBus([new NotifierMiddleware(new Notifier())]))
            ->and($subscriber = new UserEventSubscriber())
            ->and($eventBus->addSubscriber($subscriber))
            ->then()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::FOO_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::BAR_EVENT))
                    ->isTrue()
                ->boolean($eventBus->hasListeners(UserEventSubscriber::USER_LOGIN))
                    ->isTrue()
                ->and()
                ->when($eventBus->removeSubscriber($subscriber))
                ->then()
                    ->boolean($eventBus->hasListeners(UserEventSubscriber::FOO_EVENT))
                        ->isFalse()
                    ->boolean($eventBus->hasListeners(UserEventSubscriber::BAR_EVENT))
                        ->isFalse()
                    ->boolean($eventBus->hasListeners(UserEventSubscriber::USER_LOGIN))
                        ->isFalse()
        ;
    }
}
