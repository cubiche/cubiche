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
 * WriteException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class WriteException extends RuntimeException
{
    /**
     * Creates a new exception.
     *
     * @param Exception $exception
     *
     * @return WriteException
     */
    public static function forException(Exception $exception)
    {
        return new static(sprintf(
            'Could not write key-value store: %s',
            $exception->getMessage()
        ), $exception->getCode(), $exception);
    }
}
