<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\CallableResolver;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Handler\MethodName\HandlerMethodNameResolverInterface;
use Cubiche\Core\Bus\MessageInterface;

/**
 * CallableResolver interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageCallableResolver implements MessageCallableResolverInterface
{
    /**
     * @var HandlerMethodNameResolverInterface
     */
    private $methodNameResolver;

    /**
     * MessageCallableResolver constructor.
     *
     * @param HandlerMethodNameResolverInterface $methodNameResolver
     */
    public function __construct(HandlerMethodNameResolverInterface $methodNameResolver)
    {
        $this->methodNameResolver = $methodNameResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message, $handler): callable
    {
        if (is_callable($handler)) {
            return $handler;
        }

        if (is_object($handler)) {
            $handlerMethod = $this->methodNameResolver->resolve($message);
            if (method_exists($handler, $handlerMethod)) {
                return [$handler, $handlerMethod];
            }
        }

        throw NotFoundException::cannotHandleMessage(get_class($handler), get_class($message));
    }
}
