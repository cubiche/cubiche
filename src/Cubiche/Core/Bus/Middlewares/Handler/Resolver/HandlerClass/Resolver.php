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

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\LocatorInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\CommandName\ResolverInterface as CommandNameResolverInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\MethodName\ResolverInterface as MethodNameResolverInterface;
use Cubiche\Core\Delegate\Delegate;

/**
 * Resolver.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Resolver implements ResolverInterface
{
    /**
     * @var CommandNameResolverInterface
     */
    protected $nameResolver;

    /**
     * @var MethodNameResolverInterface
     */
    protected $methodNameResolver;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * DefaultResolver constructor.
     *
     * @param CommandNameResolverInterface $nameResolver
     * @param MethodNameResolverInterface  $methodNameResolver
     * @param LocatorInterface             $locator
     */
    public function __construct(
        CommandNameResolverInterface $nameResolver,
        MethodNameResolverInterface $methodNameResolver,
        LocatorInterface $locator
    ) {
        $this->nameResolver = $nameResolver;
        $this->methodNameResolver = $methodNameResolver;
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        $name = $this->nameResolver->resolve($command);
        $handler = $this->getHandlerFor($name);

        if ($handler === null) {
            throw NotFoundException::handlerFor($command);
        }

        $methodName = $this->methodNameResolver->resolve($command);

        return Delegate::fromMethod($handler, $methodName);
    }

    /**
     * @param $name
     *
     * @return object
     */
    protected function getHandlerFor($name)
    {
        /* @var LocatorInterface $locator */
        return $this->locator->locate($name);
    }

    /**
     * @param string $className
     * @param mixed  $handler
     */
    public function addHandler($className, $handler)
    {
        $this->locator->addHandler($className, $handler);
    }
}
