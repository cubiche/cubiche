<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Command;

use Cubiche\Core\MessageBus\Exception\NotFoundException;
use Cubiche\Core\MessageBus\MessageBus;
use Cubiche\Core\MessageBus\MessageInterface;
use Cubiche\Core\MessageBus\Middlewares\Handler\CommandHandlerMiddleware;

/**
 * CommandBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBus extends MessageBus
{
    /**
     * @return CommandBus
     */
    public static function create()
    {
        return new static([new LockingMiddleware(), new NotifierMiddleware(new Notifier())]);
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
        foreach ($this->middlewares as $priority => $middleware) {
            if ($middleware instanceof CommandHandlerMiddleware) {
                return;
            }
        }

        throw NotFoundException::middlewareOfType(CommandHandlerMiddleware::class);
    }
}
