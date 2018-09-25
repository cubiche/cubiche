<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Factory;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\LocatorInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameAndSuffixResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\ChainResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;

/**
 * HandlerClassResolverFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerClassResolverFactory
{
    /**
     * @param LocatorInterface $locator
     *
     * @return HandlerClassResolver
     */
    public function createForCommand(LocatorInterface $locator)
    {
        return $this->createFor($locator, 'Command');
    }

    /**
     * @param LocatorInterface $locator
     *
     * @return HandlerClassResolver
     */
    public function createForCommandValidator(LocatorInterface $locator)
    {
        return $this->createForValidator($locator, 'Command');
    }

    /**
     * @param LocatorInterface $locator
     *
     * @return HandlerClassResolver
     */
    public function createForQuery(LocatorInterface $locator)
    {
        return $this->createFor($locator, 'Query');
    }

    /**
     * @param LocatorInterface $locator
     *
     * @return HandlerClassResolver
     */
    public function createForEvent(LocatorInterface $locator)
    {
        return $this->createFor($locator, 'Event');
    }

    /**
     * @param LocatorInterface $locator
     *
     * @return HandlerClassResolver
     */
    public function createForQueryValidator(LocatorInterface $locator)
    {
        return $this->createForValidator($locator, 'Query');
    }

    /**
     * @param LocatorInterface $locator
     * @param string           $suffix
     *
     * @return HandlerClassResolver
     */
    protected function createForValidator(LocatorInterface $locator, $suffix)
    {
        return $this->createFor($locator, $suffix, true);
    }

    /**
     * @param LocatorInterface $locator
     * @param string           $suffix
     * @param bool             $validator
     *
     * @return HandlerClassResolver
     */
    protected function createFor(LocatorInterface $locator, $suffix, $validator = false)
    {
        if ($validator) {
            $handlerMethodNameResolver = new MethodWithShortObjectNameAndSuffixResolver($suffix, 'Validator');
        } else {
            $handlerMethodNameResolver = new MethodWithShortObjectNameResolver($suffix);
        }

        return new HandlerClassResolver(
            new ChainResolver([
                new FromMessageNamedResolver(),
                new FromClassNameResolver(),
            ]),
            $handlerMethodNameResolver,
            $locator
        );
    }
}
