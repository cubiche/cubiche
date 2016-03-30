<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Command\Middlewares\Handler;

use Cubiche\Domain\Command\MiddlewareInterface;
use Cubiche\Domain\Command\Middlewares\Handler\Resolver\HandlerClass\ResolverInterface;

/**
 * HandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var ResolverInterface
     */
    protected $handlerResolver;

    /**
     * HandlerMiddleware constructor.
     *
     * @param ResolverInterface $handlerResolver
     */
    public function __construct(ResolverInterface $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        $handler = $this->handlerResolver->resolve($command);

        $returnValue = $handler($command);

        $next($command);

        return $returnValue;
    }
}
