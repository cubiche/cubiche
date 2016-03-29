<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Domain\Command\Exception\NotFoundException;
use Cubiche\Domain\Command\Exception\InvalidResolverException;

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
     * @return string
     *
     * @throws NotFoundException
     * @throws InvalidResolverException
     */
    public function resolve($command);
}
