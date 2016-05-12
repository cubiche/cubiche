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
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Handler\CommandHandlerMiddleware;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Tests\Fixtures\Command\EncodePasswordCommand;
use Cubiche\Core\Bus\Tests\Fixtures\Command\EncodePasswordHandler;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * CommandBusTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBusTests extends TestCase
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
                ->exception(function () use ($commandBus) {
                    $commandBus->dispatch(new EncodePasswordCommand('plainpassword'));
                })
                ->isInstanceOf(NotFoundException::class)
        ;
    }

    /**
     * Test dispatch chained middlewares.
     */
    public function testDispatchChainedMiddlewares()
    {
        $this
            ->given($commandBus = CommandBus::create())
            ->and($command = new EncodePasswordCommand('plainpassword'))
            ->and($commandBus->addHandler(EncodePasswordCommand::class, new EncodePasswordHandler('md5')))
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

    /**
     * Test handlerMiddleware method.
     */
    public function testHandlerMiddleware()
    {
        $this
            ->given($commandBus = CommandBus::create())
            ->when($middleware = $commandBus->handlerMiddleware())
            ->then()
                ->object($middleware)
                    ->isInstanceOf(CommandHandlerMiddleware::class)
        ;
    }
}
