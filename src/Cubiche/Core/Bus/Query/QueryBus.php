<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Query;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageBus;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\QueryHandlerMiddleware;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\Resolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\MethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\QueryName\ChainResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\QueryName\DefaultResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\QueryName\QueryNamedResolver;

/**
 * QueryBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBus extends MessageBus
{
    /**
     * @return QueryBus
     */
    public static function create()
    {
        return new static([
            100 => new QueryHandlerMiddleware(new Resolver(
                new ChainResolver(
                    new DefaultResolver(),
                    new QueryNamedResolver()
                ),
                new MethodWithShortObjectNameResolver(),
                new InMemoryLocator()
            )),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $command)
    {
        if (!$command instanceof QueryInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    QueryInterface::class,
                    get_class($command)
                )
            );
        }

        $this->ensureQueryHandlerMiddleware();

        parent::dispatch($command);
    }

    /**
     * Ensure that exists an command handler middleware.
     *
     * @throws NotFoundException
     */
    protected function ensureQueryHandlerMiddleware()
    {
        foreach ($this->middlewares as $priority => $middleware) {
            if ($middleware instanceof QueryHandlerMiddleware) {
                return;
            }
        }

        throw NotFoundException::middlewareOfType(QueryHandlerMiddleware::class);
    }
}
