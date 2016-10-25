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
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\DefaultResolver as HandlerMethodNameDefaultResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\CommandHandlerMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand\FromClassNameResolver;
use Cubiche\Core\Cqrs\Tests\Fixtures\Command\LoginUserCommand;
use Cubiche\Core\Cqrs\Tests\Fixtures\Command\LoginUserCommandHandler;
use Cubiche\Core\Cqrs\Tests\Units\TestCase;

/**
 * CommandHandlerMiddlewareTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandHandlerMiddlewareTests extends TestCase
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
                    new HandlerMethodNameDefaultResolver(),
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
                    new FromClassNameResolver(),
                    new HandlerMethodNameDefaultResolver(),
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
