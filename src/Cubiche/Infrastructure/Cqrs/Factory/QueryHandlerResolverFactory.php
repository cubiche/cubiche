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
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\ChainResolver as NameOfQueryChainResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\FromClassNameResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\FromQueryNamedResolver;

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
            new NameOfQueryChainResolver([
                new FromQueryNamedResolver(),
                new FromClassNameResolver(),
            ]),
            new MethodWithShortObjectNameResolver('Query'),
            $this->locator
        );
    }
}
