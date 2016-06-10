<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\LocatorInterface as HandlerClassLocatorInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\ResolverInterface as HandlerMethodNameResolverInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\ResolverInterface as NameOfMessageResolverInterface;
use Cubiche\Core\Delegate\Delegate;

/**
 * HandlerClassResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerClassResolver implements ResolverInterface
{
    /**
     * @var NameOfMessageResolverInterface
     */
    protected $nameOfMessageResolver;

    /**
     * @var HandlerMethodNameResolverInterface
     */
    protected $handlerMethodNameResolver;

    /**
     * @var HandlerClassLocatorInterface
     */
    protected $handlerClassLocator;

    /**
     * Resolver constructor.
     *
     * @param NameOfMessageResolverInterface     $nameOfMessageResolver
     * @param HandlerMethodNameResolverInterface $handlerMethodNameResolver
     * @param HandlerClassLocatorInterface       $handlerClassLocator
     */
    public function __construct(
        NameOfMessageResolverInterface $nameOfMessageResolver,
        HandlerMethodNameResolverInterface $handlerMethodNameResolver,
        HandlerClassLocatorInterface $handlerClassLocator
    ) {
        $this->nameOfMessageResolver = $nameOfMessageResolver;
        $this->handlerMethodNameResolver = $handlerMethodNameResolver;
        $this->handlerClassLocator = $handlerClassLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        $nameOfMessage = $this->nameOfMessageResolver->resolve($message);
        $handler = $this->getHandlerFor($nameOfMessage);

        $handlerMethodName = $this->getHandlerMethodFor($nameOfMessage);
        if (!method_exists($handler, $handlerMethodName)) {
            throw NotFoundException::methodForObject($message, $handlerMethodName);
        }

        return Delegate::fromMethod($handler, $handlerMethodName);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerFor($className)
    {
        /* @var HandlerClassLocatorInterface $handlerClassLocator */
        return $this->handlerClassLocator->locate($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerMethodFor($className)
    {
        return $this->handlerMethodNameResolver->resolve($className);
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler($className, $handler)
    {
        $this->handlerClassLocator->addHandler($className, $handler);
    }
}
