<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Command;

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\CommandHandlerMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand\ChainResolver as NameOfCommandChainResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand\FromClassNameResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfCommand\FromCommandNamedResolver;

/**
 * CommandBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBus extends Bus
{
    /**
     * @var CommandHandlerMiddleware
     */
    protected $commandHandlerMiddleware;

    /**
     * @return CommandBus
     */
    public static function create()
    {
        return new static([
            250 => new LockingMiddleware(),
            100 => new CommandHandlerMiddleware(new HandlerClassResolver(
                new NameOfCommandChainResolver([
                    new FromCommandNamedResolver(),
                    new FromClassNameResolver(),
                ]),
                new MethodWithShortObjectNameResolver('Command'),
                new InMemoryLocator()
            )),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $command)
    {
        if (!$command instanceof CommandInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    CommandInterface::class,
                    get_class($command)
                )
            );
        }

        $this->ensureCommandHandlerMiddleware();

        parent::dispatch($command);
    }

    /**
     * Ensure that exists an command handler middleware.
     *
     * @throws NotFoundException
     */
    protected function ensureCommandHandlerMiddleware()
    {
        if ($this->commandHandlerMiddleware !== null) {
            return;
        }

        foreach ($this->middlewares as $priority => $collection) {
            foreach ($collection as $middleware) {
                if ($middleware instanceof CommandHandlerMiddleware) {
                    $this->commandHandlerMiddleware = $middleware;

                    return;
                }
            }
        }

        throw NotFoundException::middlewareOfType(CommandHandlerMiddleware::class);
    }

    /**
     * @return CommandHandlerMiddleware
     */
    public function handlerMiddleware()
    {
        $this->ensureCommandHandlerMiddleware();

        return $this->commandHandlerMiddleware;
    }

    /**
     * @param string $commandName
     * @param mixed  $commandHandler
     */
    public function addHandler($commandName, $commandHandler)
    {
        $this->ensureCommandHandlerMiddleware();

        $this->commandHandlerMiddleware->resolver()->addHandler($commandName, $commandHandler);
    }

    /**
     * @param string $commandName
     *
     * @return object
     */
    public function getHandlerFor($commandName)
    {
        $this->ensureCommandHandlerMiddleware();

        return $this->commandHandlerMiddleware->resolver()->getHandlerFor($commandName);
    }
}
