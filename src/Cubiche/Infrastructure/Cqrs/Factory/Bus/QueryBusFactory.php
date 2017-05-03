<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Factory\Bus;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerClass\HandlerClassResolver;
use Cubiche\Core\Bus\Middlewares\Validator\ValidatorMiddleware;
use Cubiche\Core\Cqrs\Middlewares\Handler\QueryHandlerMiddleware;
use Cubiche\Core\Cqrs\Query\QueryBus;

/**
 * QueryBusFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBusFactory
{
    /**
     * @param HandlerClassResolver $queryHandlerResolver
     * @param HandlerClassResolver $validatorHandlerResolver
     *
     * @return QueryBus
     */
    public function create(HandlerClassResolver $queryHandlerResolver, HandlerClassResolver $validatorHandlerResolver)
    {
        return new QueryBus([
            250 => new ValidatorMiddleware($validatorHandlerResolver),
            100 => new QueryHandlerMiddleware($queryHandlerResolver),
        ]);
    }
}
