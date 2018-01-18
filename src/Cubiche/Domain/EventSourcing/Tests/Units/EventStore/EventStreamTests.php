<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units\EventStore;

use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostId;

/**
 * EventStreamTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class EventStreamTests extends TestCase
{
    /**
     * Test id method.
     */
    public function testId()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream($postId, []))
            ->then()
                ->object($eventStream->id())
                    ->isEqualTo($postId)
        ;
    }

    /**
     * Test setId method.
     */
    public function testSetId()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->when($anotherId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream($postId, []))
            ->then()
                ->boolean($eventStream->id()->equals($postId))
                    ->isTrue()
                ->and()
                ->when($eventStream->setId($anotherId))
                ->then()
                    ->boolean($eventStream->id()->equals($postId))
                        ->isFalse()
                    ->boolean($eventStream->id()->equals($anotherId))
                        ->isTrue()
        ;
    }

    /**
     * Test iterator methods.
     */
    public function testIterator()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and(
                $event1 = new PostWasCreated($postId, 'foo', 'bar'),
                $event2 = new PostTitleWasChanged($postId, 'title-1'),
                $event3 = new PostWasUnPublished($postId)
            )
            ->and(
                $eventStream = new EventStream(
                    $postId,
                    [$event1, $event2, $event3]
                )
            )
            ->then()
                ->object($eventStream->current())
                    ->isEqualTo($event1)
                ->when($eventStream->next())
                ->then()
                ->object($eventStream->current())
                    ->isEqualTo($event2)
                ->when($eventStream->next())
                ->then()
                ->object($eventStream->current())
                    ->isEqualTo($event3)
                ->integer($eventStream->key())
                    ->isEqualTo(2)
                ->boolean($eventStream->valid())
                    ->isTrue()
                ->when($eventStream->next())
                ->then()
                ->boolean($eventStream->valid())
                    ->isFalse()
                ->when($eventStream->rewind())
                ->then()
                ->boolean($eventStream->valid())
                    ->isTrue()
                ->object($eventStream->current())
                    ->isEqualTo($event1)
        ;
    }

    /**
     * Test Events method.
     */
    public function testEvents()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream($postId, []))
            ->then()
                ->array(iterator_to_array($eventStream->events()))
                    ->isEmpty()
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream($postId, [new PostWasCreated($postId, 'foo', 'bar')]))
            ->then()
                ->array(iterator_to_array($eventStream->events()))
                    ->hasSize(1)
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->then()
                ->exception(function () use ($postId) {
                    new EventStream($postId, ['bar']);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
