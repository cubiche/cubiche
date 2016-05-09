<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\MethodName;

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\Exception\NotFoundException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the handler method name from a given command.
     *
     * @param CommandInterface $command
     *
     * @return string
     *
     * @throws NotFoundException
     */
    public function resolve(CommandInterface $command);
}
