<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Domain\Command\Exception\InvalidLocatorException;
use Cubiche\Domain\Command\Exception\NotFoundException;
use Cubiche\Domain\Command\Middlewares\Handler\Locator\LocatorInterface;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\ClassName\ResolverInterface as ClassNameResolverInterface;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\MethodName\ResolverInterface as MethodNameResolverInterface;
use Cubiche\Domain\Delegate\Delegate;

/**
 * DefaultResolver.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultResolver implements ResolverInterface
{
    /**
     * @var ClassNameResolverInterface
     */
    protected $classNameResolver;

    /**
     * @var MethodNameResolverInterface
     */
    protected $methodNameResolver;

    /**
     * @var LocatorInterface[]
     */
    protected $locators;

    /**
     * DefaultResolver constructor.
     *
     * @param ClassNameResolverInterface  $classNameResolver
     * @param MethodNameResolverInterface $methodNameResolver
     * @param array                       $locators
     */
    public function __construct(
        ClassNameResolverInterface $classNameResolver,
        MethodNameResolverInterface $methodNameResolver,
        array $locators
    ) {
        $this->classNameResolver = $classNameResolver;
        $this->methodNameResolver = $methodNameResolver;
        $this->locators = $locators;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        $commandName = $this->classNameResolver->resolve($command);
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
        while ($locator = array_shift($this->locators)) {
            if (!$locator instanceof LocatorInterface) {
                throw InvalidLocatorException::forLocator($locator);
            }

            try {
                return $locator->locate($commandName);
            } catch (\Exception $exception) {
            }
        }
    }
}
