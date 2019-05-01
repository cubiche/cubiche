<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares;

use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassResolver;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\Middlewares\QueryHandlerMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Bus\Tests\Fixtures\Query\VenuesQueryHandler;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * QueryHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryHandlerMiddlewareTests extends TestCase
{
    /**
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given($query = new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and(
                $resolver = new MessageHandlerResolver(
                    new ClassBasedNameResolver(),
                    new ShortNameFromClassResolver(),
                    new InMemoryLocator([NearbyVenuesQuery::class => $queryHandler])
                )
            )
            ->and($middleware = new QueryHandlerMiddleware($resolver))
            ->and($callable = function (array $result) {
                return json_encode($result);
            })
            ->when($result = $middleware->handle($query, $callable))
            ->then()
                ->string($result)
                    ->isNotEmpty()
                    ->isEqualTo(json_encode($queryHandler->nearbyVenues($query)))
        ;
    }
}
