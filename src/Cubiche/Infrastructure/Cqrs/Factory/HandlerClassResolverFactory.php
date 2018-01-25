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
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * HandlerClassResolverFactory constructor.
     *
     * @param LocatorInterface $locator
     */
    public function __construct(LocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return HandlerClassResolver
     */
    public function createForCommand()
    {
        return $this->createFor('Command');
    }

    /**
     * @return HandlerClassResolver
     */
    public function createForCommandValidator()
    {
        return $this->createForValidator('Command');
    }

    /**
     * @return HandlerClassResolver
     */
    public function createForQuery()
    {
        return $this->createFor('Query');
    }

    /**
     * @return HandlerClassResolver
     */
    public function createForQueryValidator()
    {
        return $this->createForValidator('Query');
    }

    /**
     * @param string $suffix
     *
     * @return HandlerClassResolver
     */
    protected function createForValidator($suffix)
    {
        return $this->createFor($suffix, true);
    }

    /**
     * @param string $suffix
     * @param bool   $validator
     *
     * @return HandlerClassResolver
     */
    protected function createFor($suffix, $validator = false)
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
            $this->locator
        );
    }
}
