<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units\EventStore;

use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\EventStore\StreamName;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostId;
use Cubiche\Domain\System\StringLiteral;

/**
 * EventStoreTestCase class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
abstract class EventStoreTestCase extends TestCase
{
    /**
     * @return EventStoreInterface
     */
    abstract protected function createStore();

    /**
     * Test Persist method.
     */
    public function testPersist()
    {
        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = new StreamName($postId, StringLiteral::fromNative('post')))
            ->and(
                $postWasCreated = new PostWasCreated($postId, 'foo', 'bar'),
                $postWasCreated->setVersion(1)
            )
            ->and($eventStream = new EventStream($streamName, [$postWasCreated]))
            ->when($store->persist($eventStream))
            ->then()
                ->object($store->load($streamName)->streamName())
                    ->isEqualTo($eventStream->streamName())
        ;

        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = new StreamName($postId, StringLiteral::fromNative('post')))
            ->and(
                $postWasCreated = new PostWasCreated($postId, 'foo', 'bar'),
                $postWasCreated->setVersion(1),
                $postTitleWasChanged = new PostTitleWasChanged($postId, 'new title'),
                $postTitleWasChanged->setVersion(2)
            )
            ->and($eventStream = new EventStream($streamName, [$postWasCreated, $postTitleWasChanged]))
            ->when($expextedVersion = $store->persist($eventStream))
            ->then()
                ->integer($expextedVersion)
                    ->isEqualTo(2)
                ->object($store->load($streamName, 2)->streamName())
                    ->isEqualTo($eventStream->streamName())
        ;
    }

    /**
     * Test Load method.
     */
    public function testLoad()
    {
        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($postId1 = PostId::fromNative(md5(rand())))
            ->and($streamName = new StreamName($postId, StringLiteral::fromNative('post')))
            ->and($streamName1 = new StreamName($postId1, StringLiteral::fromNative('post')))
            ->and(
                $postWasCreated = new PostWasCreated($postId1, 'foo', 'bar'),
                $postWasCreated->setVersion(1)
            )
            ->and(
                $postTitleWasChanged = new PostTitleWasChanged($postId1, 'new title'),
                $postTitleWasChanged->setVersion(2)
            )
            ->and($eventStream = new EventStream($streamName, []))
            ->and($eventStream1 = new EventStream($streamName1, [$postWasCreated, $postTitleWasChanged]))
            ->when($store->persist($eventStream))
            ->then()
                ->variable($store->load($streamName))
                    ->isNull()
//                ->variable($store->load(PostId::fromNative(md5(rand()))))
//                    ->isNull()
                ->and()
                ->when($store->persist($eventStream1))
                ->and($result = $store->load($streamName1))
                ->then()
                    ->object($result->streamName())
                        ->isEqualTo($eventStream1->streamName())
        ;

        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = new StreamName($postId, StringLiteral::fromNative('post')))
            ->and(
                $postWasCreated = new PostWasCreated($postId, 'foo', 'bar'),
                $postWasCreated->setVersion(1)
            )
            ->and(
                $postTitleWasChanged = new PostTitleWasChanged($postId, 'new title'),
                $postTitleWasChanged->setVersion(2)
            )
            ->and(
                $postTitleWasChangedAgain = new PostTitleWasChanged($postId, 'more title'),
                $postTitleWasChangedAgain->setVersion(3)
            )
            ->and(
                $eventStream = new EventStream(
                    $streamName,
                    [$postWasCreated, $postTitleWasChanged, $postTitleWasChangedAgain]
                )
            )
            ->and($store->persist($eventStream))
            ->when($stream = $store->load($streamName, 2))
            ->then()
                ->array(iterator_to_array($stream->events()))
                    ->hasSize(2)
        ;
    }

    /**
     * Test Remove method.
     */
    public function testRemove()
    {
        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = new StreamName($postId, StringLiteral::fromNative('post')))
            ->and($streamName1 = new StreamName(PostId::fromNative(md5(rand())), StringLiteral::fromNative('post')))
            ->and(
                $postWasCreated = new PostWasCreated($postId, 'foo', 'bar'),
                $postWasCreated->setVersion(1)
            )
            ->and(
                $postTitleWasChanged = new PostTitleWasChanged($postId, 'new title'),
                $postTitleWasChanged->setVersion(2)
            )
            ->and($eventStream = new EventStream($streamName, [$postWasCreated, $postTitleWasChanged]))
            ->and($store->persist($eventStream))
            ->when($store->remove($streamName1))
            ->then()
                ->object($store->load($streamName)->streamName())
                    ->isEqualTo($eventStream->streamName())
                ->and()
                ->when($store->remove($streamName))
                ->then()
                    ->variable($store->load($streamName))
                        ->isNull()
        ;
    }
}
