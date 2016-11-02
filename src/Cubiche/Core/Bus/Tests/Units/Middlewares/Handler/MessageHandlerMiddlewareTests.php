<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\MessageHandlerMiddleware;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessageListener;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * MessageHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageHandlerMiddlewareTests extends TestCase
{
    /**
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given(
                $resolver = new HandlerClassResolver(
                    new FromClassNameResolver(),
                    new MethodWithShortObjectNameResolver(),
                    new InMemoryLocator([LoginUserMessage::class => new LoginUserMessageListener()])
                )
            )
            ->and($middleware = new MessageHandlerMiddleware($resolver))
            ->and($command = new LoginUserMessage('ivan@cubiche.com', 'plainpassword'))
            ->and($callable = function (LoginUserMessage $command) {
                $command->setEmail('info@cubiche.org');
            })
            ->when($middleware->handle($command, $callable))
            ->then()
                ->string($command->email())
                    ->isEqualTo('info@cubiche.org')
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
                    new FromClassNameResolver(),
                    new MethodWithShortObjectNameResolver(),
                    new InMemoryLocator([LoginUserMessage::class => new LoginUserMessageListener()])
                )
            )
            ->and($middleware = new MessageHandlerMiddleware($resolver))
            ->when($result = $middleware->resolver())
            ->then()
                ->object($result)
                    ->isEqualTo($resolver)
        ;
    }
}
