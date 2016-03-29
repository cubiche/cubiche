<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\ClassName;

use Cubiche\Domain\Command\Exception\NotFoundException;
use Cubiche\Domain\Command\Exception\InvalidResolverException;
use InvalidArgumentException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the handler class name from a command.
     *
     * @param object $command
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws InvalidResolverException
     */
    public function resolve($command);
}
