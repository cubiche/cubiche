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
use Cubiche\Domain\EventSourcing\EventStore\InMemoryEventStore;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * InMemoryEventStoreTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class InMemoryEventStoreTests extends TestCase
{
    /**
     * @return InMemoryEventStore
     */
    protected function createStore()
    {
        return new InMemoryEventStore();
    }

    /**
     * Test Persist method.
     */
    public function testPersist()
    {
        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream('posts', $postId, [new PostWasCreated($postId, 'foo', 'bar')]))
            ->and($version = new Version())
            ->when($store->persist($eventStream, $version))
            ->then()
                ->object($store->load('posts', $postId, $version))
                    ->isEqualTo($eventStream)
        ;

        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and(
                $eventStream1x0 = new EventStream(
                    'posts',
                    $postId,
                    [new PostWasCreated($postId, 'foo', 'bar')]
                )
            )
            ->and(
                $eventStream2x0 = new EventStream(
                    'posts',
                    $postId,
                    [new PostWasCreated($postId, 'foo', 'bar'), new PostTitleWasChanged($postId, 'new title')]
                )
            )
            ->and($version = new Version())
            ->and(VersionManager::setCurrentApplicationVersion(Version::fromString('1.0.0')))
            ->when($store->persist($eventStream1x0, $version))
            ->then()
                ->object($store->load('posts', $postId, $version))
                    ->isEqualTo($eventStream1x0)
                ->and()
                ->when(VersionManager::setCurrentApplicationVersion(Version::fromString('2.0.0')))
                ->and($store->persist($eventStream2x0, $version))
                ->then()
                    ->object($store->load('posts', $postId, $version))
                        ->isEqualTo($eventStream2x0)
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
            ->and($eventStream = new EventStream('posts', $postId, [new PostWasCreated($postId, 'foo', 'bar')]))
            ->and($version = new Version())
            ->when($store->persist($eventStream, $version))
            ->then()
                ->exception(function () use ($store, $postId, $version) {
                    $store->load('blogs', $postId, $version);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($store, $postId) {
                    $store->load('posts', $postId, new Version(0, 10, 456));
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($store, $version) {
                    $store->load('posts', PostId::fromNative(md5(rand())), $version);
                })->isInstanceOf(\RuntimeException::class)
                ->and()
                ->when(VersionManager::setCurrentApplicationVersion(Version::fromString('2.1.0')))
                ->then()
                    ->exception(function () use ($store, $postId, $version) {
                        $store->load('posts', $postId, $version);
                    })->isInstanceOf(\RuntimeException::class)
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
            ->and($version = new Version())
            ->then()
                ->exception(function () use ($store, $postId, $version) {
                    $store->remove('posts', $postId, $version);
                })->isInstanceOf(\RuntimeException::class)
        ;

        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and($eventStream = new EventStream('posts', $postId, [new PostWasCreated($postId, 'foo', 'bar')]))
            ->and($version = new Version())
            ->when($store->persist($eventStream, $version))
            ->and($store->remove('posts', $postId, $version))
            ->then()
                ->exception(function () use ($store, $postId, $version) {
                    $store->load('posts', $postId, $version);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($store, $postId, $version) {
                    $store->remove('blogs', $postId, $version);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($store, $postId) {
                    $store->remove('posts', $postId, new Version(0, 10, 456));
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
