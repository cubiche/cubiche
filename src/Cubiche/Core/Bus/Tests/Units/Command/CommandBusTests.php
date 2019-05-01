<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Command;

use Cubiche\Core\Bus\Command\CommandBus;
use Cubiche\Core\Bus\Middlewares\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Units\BusTests;
use Cubiche\Core\Bus\Tests\Fixtures\Command\EncodePasswordCommand;
use Cubiche\Core\Bus\Tests\Fixtures\Command\EncodePasswordHandler;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;

/**
 * CommandBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBusTests extends BusTests
{
    /**
     * Test create without command handler middleware.
     */
    public function testCreateWithoutCommandHandlerMiddleware()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($commandBus = new CommandBus([$middleware]))
            ->then()
                // test that nothing happens. No exception is raised.
                ->variable($commandBus->dispatch(new EncodePasswordCommand('plainpassword')))
                    ->isNull()
        ;
    }

    /**
     * Test dispatch chained middlewares.
     */
    public function testDispatchChainedMiddlewares()
    {
        $this
            ->given(
                $commandBus = CommandBus::create([
                    EncodePasswordCommand::class => new EncodePasswordHandler('md5')
                ])
            )
            ->and($command = new EncodePasswordCommand('plainpassword'))
            ->and($commandBus->dispatch($command))
            ->then()
                ->string($command->password())
                    ->isEqualTo(md5('plainpassword'))
        ;
    }

    /**
     * Test dispatch with invalid command.
     */
    public function testDispatchWithInvalidCommand()
    {
        $this
            ->given($commandBus = CommandBus::create())
            ->then()
                ->exception(function () use ($commandBus) {
                    $commandBus->dispatch(new FooMessage());
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
