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

/**
 * MethodWithObjectNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $handler->changeTitleCommand()
 *  - Cubiche\Application\Query\PostListQuery => $handler->postListQuery()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithObjectNameResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        $commandName = get_class($command);

        // If class name has a namespace separator, only take last portion
        if (strpos($commandName, '\\') !== false) {
            $commandName = substr($commandName, strrpos($commandName, '\\') + 1);
        }

        return lcfirst($commandName);
    }
}
