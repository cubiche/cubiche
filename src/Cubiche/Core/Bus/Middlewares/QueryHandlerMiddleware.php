<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares;

use Cubiche\Core\Bus\Query\QueryInterface;

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
    public function handle($message, callable $next)
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

        $handler = $this->messageHandlerResolver->resolve($message);

        // get the query result
        $returnValue = call_user_func($handler, $message);

        // pass the query result to the next middleware
        // and return the last value
        return $next($returnValue);
    }
}
