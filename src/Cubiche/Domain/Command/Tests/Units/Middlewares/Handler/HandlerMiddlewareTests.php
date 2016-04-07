<?php

/**
 * This file is part of the Cubiche/Command component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Tests\Units\Middlewares\Handler;

use Cubiche\Domain\Command\Middlewares\Handler\HandlerMiddleware;
use Cubiche\Domain\Command\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\ClassName\DefaultResolver as ClassNameDefaultResolver;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\HandlerClass\DefaultResolver as HandlerClassDefaultResolver;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\MethodName\DefaultResolver as MethodNameDefaultResolver;
use Cubiche\Domain\Command\Tests\Fixtures\LoginUserCommand;
use Cubiche\Domain\Command\Tests\Fixtures\LoginUserCommandHandler;
use Cubiche\Domain\Command\Tests\Units\TestCase;

/**
 * HandlerMiddleware class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class HandlerMiddlewareTests extends TestCase
{
    /**
     * Test Execute method.
     */
    public function testExecute()
    {
        $this
            ->given(
                $resolver = new HandlerClassDefaultResolver(
                    new ClassNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    [new InMemoryLocator([LoginUserCommand::class => new LoginUserCommandHandler()])]
                )
            )
            ->and($middleware = new HandlerMiddleware($resolver))
            ->and($command = new LoginUserCommand('ivan@timeout.com', 'plainpassword'))
            ->and($callable = function (LoginUserCommand $command) {
                $command->setEmail('info@cubiche.org');
            })
            ->when($result = $middleware->execute($command, $callable))
            ->then()
                ->boolean($result)
                    ->isTrue()
                ->string($command->email())
                    ->isEqualTo('info@cubiche.org')
        ;
    }
}
