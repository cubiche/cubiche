<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Tests\Units\Query;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Units\BusTests;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Core\Cqrs\Tests\Fixtures\FooMessage;
use Cubiche\Core\Cqrs\Tests\Fixtures\JsonEncodeMiddleware;
use Cubiche\Core\Cqrs\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Cqrs\Tests\Fixtures\Query\PublishedPostsQuery;
use Cubiche\Core\Cqrs\Tests\Fixtures\Query\VenuesQueryHandler;

/**
 * QueryBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBusTests extends BusTests
{
    /**
     * Test create without query handler middleware.
     */
    public function testCreateWithoutQueryHandlerMiddleware()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($queryBus = new QueryBus([$middleware]))
            ->then()
                ->exception(function () use ($queryBus) {
                    $queryBus->dispatch(new PublishedPostsQuery(new \DateTime()));
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
            ->given($queryBus = QueryBus::create())
            ->and($query = new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and($queryBus->addHandler($query->named(), $queryHandler))
            ->and($result = $queryBus->dispatch($query))
            ->then()
                ->array($result)
                    ->hasSize(2)
        ;

        $this
            ->given($queryBus = QueryBus::create())
            ->and($queryBus->addMiddlewareAfter(new JsonEncodeMiddleware(), $queryBus->handlerMiddleware()))
            ->and($query = new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and($queryBus->addHandler($query->named(), $queryHandler))
            ->and($result = $queryBus->dispatch($query))
            ->then()
                ->string($result)
                    ->isNotEmpty()
                    ->isEqualTo(json_encode($queryHandler->aroundVenues($query)))
        ;
    }

    /**
     * Test dispatch with invalid query.
     */
    public function testDispatchWithInvalidQuery()
    {
        $this
            ->given($queryBus = QueryBus::create())
            ->then()
                ->exception(function () use ($queryBus) {
                    $queryBus->dispatch(new FooMessage());
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
