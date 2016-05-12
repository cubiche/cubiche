<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Units;

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;

/**
 * BusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BusTests extends TestCase
{
    /**
     * Test create with invalid middleware.
     */
    public function testCreateWithInvalidMiddleware()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->then()
                ->exception(function () use ($middleware) {
                    new Bus([$middleware, new \StdClass()]);
                })
                ->isInstanceOf(InvalidMiddlewareException::class)
        ;
    }

    /**
     * Test dispatch method.
     */
    public function testDispatch()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($bus = new Bus([12 => $middleware]))
            ->when($result = $bus->dispatch(new FooMessage()))
            ->then()
                ->variable($result)
                    ->isNull()
        ;
    }

    /**
     * Test addMiddlewareBefore method.
     */
    public function testAddMiddlewareBefore()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($bus = new Bus([12 => $middleware]))
            ->when($result = $bus->addMiddlewareBefore($middleware, $middleware))
            ->then()
                ->variable($result)
                    ->isNull()
        ;

        $this
            ->given($middleware = new LockingMiddleware())
            ->and($bus = new Bus())
            ->then()
                ->exception(function () use ($bus, $middleware) {
                    $bus->addMiddlewareBefore($middleware, $middleware);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test addMiddlewareAfter method.
     */
    public function testAddMiddlewareAfter()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($bus = new Bus([12 => $middleware]))
            ->when($result = $bus->addMiddlewareAfter($middleware, $middleware))
            ->then()
                ->variable($result)
                    ->isNull()
        ;

        $this
            ->given($middleware = new LockingMiddleware())
            ->and($bus = new Bus())
            ->then()
                ->exception(function () use ($bus, $middleware) {
                    $bus->addMiddlewareAfter($middleware, $middleware);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
