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
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\ChainResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;

/**
 * QueryHandlerResolverFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryHandlerResolverFactory
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * QueryHandlerResolverFactory constructor.
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
    public function create()
    {
        return new HandlerClassResolver(
            new ChainResolver([
                new FromMessageNamedResolver(),
                new FromClassNameResolver(),
            ]),
            new MethodWithShortObjectNameResolver('Query'),
            $this->locator
        );
    }
}
