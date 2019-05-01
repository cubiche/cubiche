<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\MethodName;

use Cubiche\Core\Bus\MessageInterface;

/**
 * NameFromClassResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitleCommand()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postListQuery()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NameFromClassResolver implements HandlerMethodNameResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        $className = get_class($message);
        // If class name has a namespace separator, only take last portion
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }

        return lcfirst($className);
    }
}
