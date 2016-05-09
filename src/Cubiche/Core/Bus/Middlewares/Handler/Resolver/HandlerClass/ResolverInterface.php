<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Exception\InvalidResolverException;
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
     * @param CommandInterface $command
     *
     * @return callable
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws InvalidResolverException
     */
    public function resolve(CommandInterface $command);

    /**
     * @param string $className
     * @param mixed  $handler
     */
    public function addHandler($className, $handler);
}
