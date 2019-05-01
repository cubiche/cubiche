<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Handler\Resolver;

use Cubiche\Core\Bus\Handler\CallableResolver\MessageCallableResolver;
use Cubiche\Core\Bus\Handler\CallableResolver\MessageCallableResolverInterface;
use Cubiche\Core\Bus\Handler\Locator\HandlerLocatorInterface;
use Cubiche\Core\Bus\Handler\MethodName\HandlerMethodNameResolverInterface;
use Cubiche\Core\Bus\Message\Resolver\MessageNameResolverInterface;
use Cubiche\Core\Bus\MessageInterface;

/**
 * MessageHandlerResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageHandlerResolver implements MessageHandlerResolverInterface
{
    /**
     * @var MessageNameResolverInterface
     */
    protected $messageNameResolver;

    /**
     * @var MessageCallableResolverInterface
     */
    protected $messageCallableResolver;

    /**
     * @var HandlerLocatorInterface
     */
    protected $handlerClassLocator;

    /**
     * Resolver constructor.
     *
     * @param MessageNameResolverInterface       $messageNameResolver
     * @param HandlerMethodNameResolverInterface $handlerMethodNameResolver
     * @param HandlerLocatorInterface            $handlerClassLocator
     */
    public function __construct(
        MessageNameResolverInterface $messageNameResolver,
        HandlerMethodNameResolverInterface $handlerMethodNameResolver,
        HandlerLocatorInterface $handlerClassLocator
    ) {
        $this->messageNameResolver = $messageNameResolver;
        $this->messageCallableResolver = new MessageCallableResolver($handlerMethodNameResolver);
        $this->handlerClassLocator = $handlerClassLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): callable
    {
        $messageName = $this->messageNameResolver->resolve($message);
        $handler = $this->handlerClassLocator->locate($messageName);

        return $this->messageCallableResolver->resolve($message, $handler);
    }
}
