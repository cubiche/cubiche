<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Middlewares\Handler\Resolver\MethodName;

use Cubiche\Domain\CommandBus\Exception\NotFoundException;

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
     * @param object $command
     *
     * @return string
     *
     * @throws NotFoundException
     */
    public function resolve($command);
}
