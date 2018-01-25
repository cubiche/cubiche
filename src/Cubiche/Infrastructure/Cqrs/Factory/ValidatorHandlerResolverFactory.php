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
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\ChainResolver as HandlerMethodNameChainResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameAndSuffixResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\ChainResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromClassNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage\FromMessageNamedResolver;

/**
 * ValidatorHandlerResolverFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValidatorHandlerResolverFactory
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * ValidatorHandlerResolverFactory constructor.
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
            new HandlerMethodNameChainResolver([
                new MethodWithShortObjectNameAndSuffixResolver('Command', 'Validator'),
                new MethodWithShortObjectNameAndSuffixResolver('Query', 'Validator'),
                new MethodWithShortObjectNameAndSuffixResolver('Message', 'Validator'),
            ]),
            $this->locator
        );
    }
}
