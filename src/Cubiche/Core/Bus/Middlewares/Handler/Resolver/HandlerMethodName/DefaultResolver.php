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

/**
 * DefaultResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $fooTaskCommandHandler->handle()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->handle()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve($className)
    {
        return 'handle';
    }
}
