<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Middlewares\Handler;

use Cubiche\Core\Bus\Middlewares\Handler\MessageHandlerMiddleware;
use Cubiche\Core\Cqrs\Query\QueryInterface;

/**
 * QueryHandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryHandlerMiddleware extends MessageHandlerMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function handle($query, callable $next)
    {
        $this->ensureTypeOfMessage($query);
        $handler = $this->handlerClassResolver->resolve($query);

        // get the query result
        $returnValue = $handler($query);

        // and pass the query result to the next middleware
        $returnValue = $next($returnValue);

        // return the last value
        return $returnValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function ensureTypeOfMessage($message)
    {
        if (!$message instanceof QueryInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    QueryInterface::class,
                    get_class($message)
                )
            );
        }
    }
}
