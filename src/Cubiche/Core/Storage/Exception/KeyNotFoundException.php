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
 * KeyNotFoundException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class KeyNotFoundException extends RuntimeException
{
    /**
     * Creates an exception for a key that was not found.
     *
     * @param string|int     $key
     * @param Exception|null $cause
     *
     * @return KeyNotFoundException
     */
    public static function forKey($key, Exception $cause = null)
    {
        return new static(sprintf(
            'The key "%s" does not exist.',
            $key
        ), 0, $cause);
    }
}
