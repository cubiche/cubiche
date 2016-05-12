<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName;

use Cubiche\Core\Bus\MessageInterface;

/**
 * MethodWithObjectNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitleCommand()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postListQuery()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithObjectNameResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        $messageName = get_class($message);

        // If class name has a namespace separator, only take last portion
        if (strpos($messageName, '\\') !== false) {
            $messageName = substr($messageName, strrpos($messageName, '\\') + 1);
        }

        return lcfirst($messageName);
    }
}
