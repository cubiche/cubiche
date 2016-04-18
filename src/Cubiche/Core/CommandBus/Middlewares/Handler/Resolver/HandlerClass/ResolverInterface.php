<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Core\CommandBus\Exception\NotFoundException;
use Cubiche\Core\CommandBus\Exception\InvalidResolverException;
use InvalidArgumentException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the handler class from a given command.
     *
     * @param object $command
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws InvalidResolverException
     */
    public function resolve($command);
}
