<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Exception;

use RuntimeException;
use Exception;

/**
 * InvalidKeyException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidKeyException extends RuntimeException
{
    /**
     * Creates an exception for an invalid key.
     *
     * @param mixed          $key
     * @param Exception|null $cause
     *
     * @return InvalidKeyException
     */
    public static function forKey($key, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a key of type integer or string. Got: %s',
            is_object($key) ? get_class($key) : gettype($key)
        ), 0, $cause);
    }
}
