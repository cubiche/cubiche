<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\MethodName;

/**
 * MethodWithCommandNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $handler->handleFooTaskCommand()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithCommandNameResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        $commandName = get_class($command);

        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return 'handle'.$commandName;
    }
}
