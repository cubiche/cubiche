<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\CommandBus\Exception;

use Cubiche\Core\CommandBus\MiddlewareInterface;
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
     * @param mixed          $value
     * @param Exception|null $cause
     *
     * @return InvalidMiddlewareException
     */
    public static function forUnknownValue($value, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a middleware of type %s. Got: %s',
            MiddlewareInterface::class,
            is_object($value) ? get_class($value) : gettype($value)
        ), 0, $cause);
    }
}
