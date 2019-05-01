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

use Cubiche\Core\Bus\Bus;
use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassResolver;
use Cubiche\Core\Bus\Handler\MethodName\ShortNameFromClassWithSuffixResolver;
use Cubiche\Core\Bus\Handler\Resolver\MessageHandlerResolver;
use Cubiche\Core\Bus\Message\Resolver\ClassBasedNameResolver;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Middlewares\ValidatorMiddleware;
use Cubiche\Core\Bus\Middlewares\QueryHandlerMiddleware;

/**
 * QueryBus class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBus extends Bus
{
    /**
     * @return QueryBus
     */
    public static function create(array $queryBusHandlers = [])
    {
        return new static([
            500 => new ValidatorMiddleware(new MessageHandlerResolver(
                new ClassBasedNameResolver(),
                new ShortNameFromClassWithSuffixResolver('Validator'),
                new InMemoryLocator($queryBusHandlers)
            )),
            250 => new QueryHandlerMiddleware(new MessageHandlerResolver(
                new ClassBasedNameResolver(),
                new ShortNameFromClassResolver(),
                new InMemoryLocator($queryBusHandlers)
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

        return parent::dispatch($query);
    }
}
