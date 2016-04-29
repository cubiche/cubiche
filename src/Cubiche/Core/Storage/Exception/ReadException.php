<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage\Exception;

use RuntimeException;
use Exception;

/**
 * ReadException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ReadException extends RuntimeException
{
    /**
     * Creates a new exception.
     *
     * @param Exception $exception
     *
     * @return ReadException
     */
    public static function forException(Exception $exception)
    {
        return new static(sprintf(
            'Could not read key-value store: %s',
            $exception->getMessage()
        ), $exception->getCode(), $exception);
    }
}
