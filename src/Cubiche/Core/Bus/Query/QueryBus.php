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
use Cubiche\Core\Bus\Bus;
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
class QueryBus extends Bus
{
    /**
     * @var QueryHandlerMiddleware
     */
    protected $queryHandlerMiddleware;

    /**
     * @return QueryBus
     */
    public static function create()
    {
        return new static([
            100 => new QueryHandlerMiddleware(new Resolver(
                new ChainResolver([
                    new QueryNamedResolver(),
                    new DefaultResolver(),
                ]),
                new MethodWithShortObjectNameResolver('Query'),
                new InMemoryLocator()
            )),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageInterface $query)
    {
        if (!$query instanceof QueryInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    QueryInterface::class,
                    get_class($query)
                )
            );
        }

        $this->ensureQueryHandlerMiddleware();

        return parent::dispatch($query);
    }

    /**
     * Ensure that exists an query handler middleware.
     *
     * @throws NotFoundException
     */
    protected function ensureQueryHandlerMiddleware()
    {
        if ($this->queryHandlerMiddleware !== null) {
            return;
        }

        foreach ($this->middlewares as $priority => $middleware) {
            if ($middleware instanceof QueryHandlerMiddleware) {
                $this->queryHandlerMiddleware = $middleware;

                return;
            }
        }

        throw NotFoundException::middlewareOfType(QueryHandlerMiddleware::class);
    }

    /**
     * @param string $queryName
     * @param mixed  $queryHandler
     */
    public function addHandler($queryName, $queryHandler)
    {
        $this->ensureQueryHandlerMiddleware();

        $this->queryHandlerMiddleware->resolver()->addHandler($queryName, $queryHandler);
    }
}
