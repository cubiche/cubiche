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
use Cubiche\Core\Bus\Handler\MethodName\DefaultResolver;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\Middlewares\CommandHandlerMiddleware;
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
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given(
                $resolver = new MessageHandlerResolver(
                    new ClassBasedNameResolver(),
                    new DefaultResolver(),
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
        ;
    }
}
