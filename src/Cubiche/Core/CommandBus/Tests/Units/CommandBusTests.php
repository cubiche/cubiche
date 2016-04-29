<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\CommandBus\Tests\Units;

use Cubiche\Core\CommandBus\CommandBus;
use Cubiche\Core\CommandBus\Exception\InvalidCommandException;
use Cubiche\Core\CommandBus\Exception\InvalidMiddlewareException;
use Cubiche\Core\CommandBus\Tests\Fixtures\EncoderMiddleware;
use Cubiche\Core\CommandBus\Tests\Fixtures\LoginUserCommand;

/**
 * CommandBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBusTests extends TestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($commandBus = new CommandBus([]))
            ->then()
                ->object($commandBus)
                ->isInstanceOf(CommandBus::class)
        ;
    }

    /**
     * Test create with invalid middleware.
     */
    public function testCreateWithInvalidMiddleware()
    {
        $this
            ->let($middleware = new EncoderMiddleware('sha1'))
            ->then()
            ->exception(function () use ($middleware) {
                new CommandBus([$middleware, new \StdClass()]);
            })
            ->isInstanceOf(InvalidMiddlewareException::class)
        ;
    }

    /**
     * Test handle chained middlewares.
     */
    public function testHandleChainedMiddlewares()
    {
        $this
            ->let($middleware = new EncoderMiddleware('md5'))
            ->and($command = new LoginUserCommand('ivan@cubiche.com', 'plainpassword'))
            ->and($commandBus = new CommandBus([$middleware]))
            ->when($commandBus->handle($command))
            ->then()
                ->string($command->password())
                    ->isEqualTo(md5('plainpassword'))
        ;

        $this
            ->let($middleware = new EncoderMiddleware('sha1'))
            ->and($command = new LoginUserCommand('ivan@cubiche.com', 'plainpassword'))
            ->and($commandBus = new CommandBus([$middleware, $middleware]))
            ->when($commandBus->handle($command))
            ->then()
                ->string($command->password())
                    ->isEqualTo(sha1(sha1('plainpassword')))
        ;
    }

    /**
     * Test handle with invalid command.
     */
    public function testHandleWithInvalidCommand()
    {
        $this
            ->let($middleware = new EncoderMiddleware('sha1'))
            ->and($commandBus = new CommandBus([$middleware, $middleware]))
            ->then()
                ->exception(function () use ($commandBus) {
                    $commandBus->handle('foo');
                })
                ->isInstanceOf(InvalidCommandException::class)
        ;
    }
}
