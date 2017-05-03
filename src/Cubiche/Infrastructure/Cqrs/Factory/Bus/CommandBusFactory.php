<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Factory\Bus;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\Bus\Middlewares\Validator\ValidatorMiddleware;
use Cubiche\Core\Cqrs\Command\CommandBus;
use Cubiche\Core\Cqrs\Middlewares\Handler\CommandHandlerMiddleware;

/**
 * CommandBusFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandBusFactory
{
    /**
     * @param HandlerClassResolver $commandHandlerResolver
     * @param HandlerClassResolver $validatorHandlerResolver
     *
     * @return CommandBus
     */
    public function create(HandlerClassResolver $commandHandlerResolver, HandlerClassResolver $validatorHandlerResolver)
    {
        return new CommandBus([
            500 => new LockingMiddleware(),
            250 => new ValidatorMiddleware($validatorHandlerResolver),
            100 => new CommandHandlerMiddleware($commandHandlerResolver),
        ]);
    }
}
