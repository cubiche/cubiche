<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Query;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Units\BusTests;
use Cubiche\Core\Bus\Query\QueryBus;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;
use Cubiche\Core\Bus\Tests\Fixtures\JsonEncodeMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Bus\Tests\Fixtures\Query\PublishedPostsQuery;
use Cubiche\Core\Bus\Tests\Fixtures\Query\VenuesQueryHandler;

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
                // test that nothing happens. No exception is raised.
                ->variable($queryBus->dispatch(new PublishedPostsQuery(new \DateTime())))
                    ->isNull()
        ;
    }

    /**
     * Test dispatch chained middlewares.
     */
    public function testDispatchChainedMiddlewares()
    {
        $this
            ->given($query = new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and(
                $queryBus = QueryBus::create([
                    NearbyVenuesQuery::class => new VenuesQueryHandler()
                ])
            )
            ->and($result = $queryBus->dispatch($query))
            ->then()
                ->array($result)
                    ->hasSize(2)
        ;

        $this
            ->given($query = new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and(
                $queryBus = QueryBus::create([
                    NearbyVenuesQuery::class => $queryHandler
                ])
            )
            ->and($queryBus->addMiddleware(new JsonEncodeMiddleware(), 200))
            ->and($result = $queryBus->dispatch($query))
            ->then()
                ->string($result)
                    ->isNotEmpty()
                    ->isEqualTo(json_encode($queryHandler->nearbyVenues($query)))
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
