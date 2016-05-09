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

use Cubiche\Core\Bus\MessageBus;
use Cubiche\Core\Bus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;

/**
 * MessageBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageBusTests extends TestCase
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
                    new MessageBus([$middleware, new \StdClass()]);
                })
                ->isInstanceOf(InvalidMiddlewareException::class)
        ;
    }

    /**
     * Test add middleware.
     */
    public function testAddMiddleware()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($messageBus = new MessageBus([12 => $middleware]))
            ->then()
                ->exception(function () use ($messageBus, $middleware) {
                    $messageBus->addMiddleware($middleware, 12);
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test dispatch method.
     */
    public function testDispatch()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($messageBus = new MessageBus([12 => $middleware]))
            ->when($result = $messageBus->dispatch(new FooMessage()))
            ->then()
                ->variable($result)
                    ->isNull()
        ;
    }
}
