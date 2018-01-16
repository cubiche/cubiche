<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\EventSourcing\MongoDB\Tests\Units\EventStore;

use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Units\EventStore\EventStoreTestCase;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;
use Cubiche\Infrastructure\EventSourcing\MongoDB\EventStore\MongoDBEventStore;
use Cubiche\Infrastructure\EventSourcing\MongoDB\Tests\Units\MongoDBTestCaseTrait;
use MongoDB\Driver\Exception\BulkWriteException;

/**
 * MongoDBEventStoreTests class.
 *
 * Generated by TestGenerator on 2017-05-08 at 16:09:11.
 *
 * @engine isolate
 */
class MongoDBEventStoreTests extends EventStoreTestCase
{
    use MongoDBTestCaseTrait;

    /**
     * @return string
     */
    protected function databaseName()
    {
        return MONGODB_DATABASE.'_event_store';
    }

    /**
     * @return EventStoreInterface
     */
    protected function createStore()
    {
        return new MongoDBEventStore($this->getConnection());
    }

    /**
     * Test Persist method.
     */
    public function testPersist()
    {
        parent::testPersist();

        $this
            ->given($store = $this->createStore())
            ->and($postId = PostId::fromNative(md5(rand())))
            ->and(
                $postWasCreated = new PostWasCreated($postId, 'foo', 'bar'),
                $postWasCreated->setVersion(1)
            )
            ->and(
                $postTitleWasChanged = new PostTitleWasChanged($postId, 'new title'),
                $postTitleWasChanged->setVersion(1)
            )
            ->and($eventStream = new EventStream($postId, [$postWasCreated, $postTitleWasChanged]))
            ->then()
                ->exception(function () use ($store, $eventStream) {
                    $store->persist($eventStream);
                })->isInstanceOf(BulkWriteException::class)
        ;
    }
}
