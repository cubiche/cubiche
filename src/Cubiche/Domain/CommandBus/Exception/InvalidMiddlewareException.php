<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Exception;

use InvalidArgumentException;
use Exception;

/**
 * InvalidMiddlewareException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidMiddlewareException extends InvalidArgumentException
{
    /**
     * Creates an exception for an invalid middleware.
     *
     * @param mixed          $middleware
     * @param Exception|null $cause
     *
     * @return InvalidMiddlewareException
     */
    public static function forMiddleware($middleware, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a middleware that implement the Middleware interface. Got: %s',
            is_object($middleware) ? get_class($middleware) : gettype($middleware)
        ), 0, $cause);
    }
}
