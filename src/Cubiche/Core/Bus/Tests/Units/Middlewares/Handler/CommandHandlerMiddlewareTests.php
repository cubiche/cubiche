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

use Cubiche\Core\Bus\Middlewares\Handler\CommandHandlerMiddleware;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\CommandName\DefaultResolver as CommandNameDefaultResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\Resolver as HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\MethodName\DefaultResolver as MethodNameDefaultResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Command\LoginUserCommand;
use Cubiche\Core\Bus\Tests\Fixtures\Command\LoginUserCommandHandler;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * CommandHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandHandlerMiddlewareTests extends TestCase
{
    /**
     * Test Execute method.
     */
    public function testExecute()
    {
        $this
            ->given(
                $resolver = new HandlerClassResolver(
                    new CommandNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    new InMemoryLocator([LoginUserCommand::class => new LoginUserCommandHandler()])
                )
            )
            ->and($middleware = new CommandHandlerMiddleware($resolver))
            ->and($command = new LoginUserCommand('ivan@cubiche.com', 'plainpassword'))
            ->and($callable = function (LoginUserCommand $command) {
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
                    new CommandNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    new InMemoryLocator([LoginUserCommand::class => new LoginUserCommandHandler()])
                )
            )
            ->and($middleware = new CommandHandlerMiddleware($resolver))
            ->when($result = $middleware->resolver())
            ->then()
                ->object($result)
                    ->isEqualTo($resolver)
        ;
    }
}
