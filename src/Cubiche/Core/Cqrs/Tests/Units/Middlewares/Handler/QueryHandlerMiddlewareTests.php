<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Tests\Units\Middlewares\Handler;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\QueryHandlerMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\FromQueryNamedResolver;
use Cubiche\Core\Cqrs\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Cqrs\Tests\Fixtures\Query\VenuesQueryHandler;
use Cubiche\Core\Cqrs\Tests\Units\TestCase;

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
            ->given(
                $resolver = new HandlerClassResolver(
                    new FromQueryNamedResolver(),
                    new MethodWithShortObjectNameResolver('Query'),
                    new InMemoryLocator()
                )
            )
            ->and($middleware = new QueryHandlerMiddleware($resolver))
            ->and($query = new NearByVenuesQuery($this->faker->latitude(), $this->faker->longitude()))
            ->and($queryHandler = new VenuesQueryHandler())
            ->and($resolver->addHandler($query->named(), $queryHandler))
            ->and($callable = function (array $result) {
                return json_encode($result);
            })
            ->when($result = $middleware->handle($query, $callable))
            ->then()
                ->string($result)
                    ->isNotEmpty()
                    ->isEqualTo(json_encode($queryHandler->aroundVenues($query)))
                ->exception(function () use ($middleware, $callable) {
                    $middleware->handle(new \StdClass(), $callable);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test dispatcher method.
     */
    public function testDispatcher()
    {
        $this
            ->given(
                $resolver = new HandlerClassResolver(
                    new FromQueryNamedResolver(),
                    new MethodWithShortObjectNameResolver('Query'),
                    new InMemoryLocator([NearByVenuesQuery::class => new VenuesQueryHandler()])
                )
            )
            ->and($middleware = new QueryHandlerMiddleware($resolver))
            ->when($result = $middleware->resolver())
            ->then()
                ->object($result)
                    ->isEqualTo($resolver)
        ;
    }
}
