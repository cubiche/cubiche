<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\CommandName;

use Cubiche\Core\CommandBus\Exception\NotFoundException;
use InvalidArgumentException;

/**
 * Resolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * Resolve the command name.
     *
     * @param object $command
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function resolve($command);
}
