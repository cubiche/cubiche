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
 * DefaultResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $fooTaskCommandHandler->handle()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->handle()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultResolver implements HandlerMethodNameResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        return 'handle';
    }
}
