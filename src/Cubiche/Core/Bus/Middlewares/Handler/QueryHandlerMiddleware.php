<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler;

use Cubiche\Core\Bus\Query\QueryInterface;

/**
 * QueryHandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryHandlerMiddleware extends CommandHandlerMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function handle($query, callable $next)
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

        $handler = $this->handlerResolver->resolve($query);

        // get the query result
        $returnValue = $handler($query);

        // and pass the query result to the next middleware
        $returnValue = $next($returnValue);

        // return the last value
        return $returnValue;
    }
}
