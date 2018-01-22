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
use Cubiche\Domain\EventSourcing\EventStore\StreamName;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostId;
use Cubiche\Domain\System\StringLiteral;

/**
 * EventStreamTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class EventStreamTests extends TestCase
{
    /**
     * Test streamName method.
     */
    public function testStreamName()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream(new StreamName($postId, StringLiteral::fromNative('post')), []))
            ->then()
                ->object($eventStream->streamName()->id())
                    ->isEqualTo($postId)
                ->string($eventStream->streamName()->category()->toNative())
                    ->isEqualTo('post')
                ->string($eventStream->streamName()->name()->toNative())
                    ->isEqualTo('post-'.$postId)
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
                    new StreamName($postId, StringLiteral::fromNative('post')),
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
            ->and($eventStream = new EventStream(new StreamName($postId, StringLiteral::fromNative('post')), []))
            ->then()
                ->array(iterator_to_array($eventStream->events()))
                    ->isEmpty()
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and(
                $eventStream = new EventStream(
                    new StreamName(
                        $postId,
                        StringLiteral::fromNative('post')
                    ),
                    [new PostWasCreated($postId, 'foo', 'bar')]
                )
            )
            ->then()
                ->array(iterator_to_array($eventStream->events()))
                    ->hasSize(1)
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->then()
                ->exception(function () use ($postId) {
                    new EventStream(new StreamName($postId, StringLiteral::fromNative('post')), ['bar']);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
