<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\CommandBus\Exception\InvalidLocatorException;
use Cubiche\Core\CommandBus\Exception\NotFoundException;
use Cubiche\Core\CommandBus\Middlewares\Handler\Locator\LocatorInterface;
use Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\CommandName\ResolverInterface as
    CommandNameResolverInterface;
use Cubiche\Core\CommandBus\Middlewares\Handler\Resolver\MethodName\ResolverInterface as MethodNameResolverInterface;
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
    protected $commandNameResolver;

    /**
     * @var MethodNameResolverInterface
     */
    protected $methodNameResolver;

    /**
     * @var ArrayCollection
     */
    protected $locators;

    /**
     * DefaultResolver constructor.
     *
     * @param CommandNameResolverInterface $commandNameResolver
     * @param MethodNameResolverInterface  $methodNameResolver
     * @param array                        $locators
     */
    public function __construct(
        CommandNameResolverInterface $commandNameResolver,
        MethodNameResolverInterface $methodNameResolver,
        array $locators
    ) {
        $this->commandNameResolver = $commandNameResolver;
        $this->methodNameResolver = $methodNameResolver;

        $this->locators = new ArrayCollection();
        foreach ($locators as $locator) {
            if (!$locator instanceof LocatorInterface) {
                throw InvalidLocatorException::forUnknownValue($locator);
            }

            $this->locators->add($locator);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        $commandName = $this->commandNameResolver->resolve($command);
        $handler = $this->getHandlerForCommand($commandName);

        if ($handler === null) {
            throw NotFoundException::handlerForCommand($command);
        }

        $methodName = $this->methodNameResolver->resolve($command);

        return Delegate::fromMethod($handler, $methodName);
    }

    /**
     * @param $commandName
     *
     * @return object
     */
    protected function getHandlerForCommand($commandName)
    {
        foreach ($this->locators as $locator) {
            try {
                /* @var LocatorInterface $locator */
                $handler = $locator->locate($commandName);
                if ($handler !== null) {
                    return $handler;
                }
            } catch (\Exception $exception) {
            }
        }
    }
}
