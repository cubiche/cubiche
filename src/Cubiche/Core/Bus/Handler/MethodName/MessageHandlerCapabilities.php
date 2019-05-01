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

use Cubiche\Core\Bus\Exception\NotFoundException;
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
trait MessageHandlerCapabilities
{
    /**
     * @var HandlerMethodNameResolverInterface;
     */
    private $methodNameStrategy;

    private function handleMessage(MessageInterface $message)
    {
        $methodName = $this->methodNameStrategy->resolve($message);
        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName], $message);
        }

        throw NotFoundException::cannotHandleMessage(static::class, get_class($message));
    }

}
