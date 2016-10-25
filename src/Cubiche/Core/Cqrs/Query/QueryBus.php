<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Query;

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName\MethodWithShortObjectNameResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\QueryHandlerMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\ChainResolver as NameOfQueryChainResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\FromClassNameResolver;
use Cubiche\Core\Cqrs\Middlewares\Handler\Resolver\NameOfQuery\FromQueryNamedResolver;

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
            250 => new QueryHandlerMiddleware(new HandlerClassResolver(
                new NameOfQueryChainResolver([
                    new FromQueryNamedResolver(),
                    new FromClassNameResolver(),
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

        foreach ($this->middlewares as $priority => $collection) {
            foreach ($collection as $middleware) {
                if ($middleware instanceof QueryHandlerMiddleware) {
                    $this->queryHandlerMiddleware = $middleware;

                    return;
                }
            }
        }

        throw NotFoundException::middlewareOfType(QueryHandlerMiddleware::class);
    }

    /**
     * @return QueryHandlerMiddleware
     */
    public function handlerMiddleware()
    {
        $this->ensureQueryHandlerMiddleware();

        return $this->queryHandlerMiddleware;
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
