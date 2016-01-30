<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Storage\Exception;

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

    /**
     * Creates an exception for multiple keys that were not found.
     *
     * @param int[]|string[] $keys
     * @param Exception|null $cause
     *
     * @return KeyNotFoundException
     */
    public static function forKeys(array $keys, Exception $cause = null)
    {
        return new static(sprintf(
            'The keys "%s" does not exist.',
            implode('", "', $keys)
        ), 0, $cause);
    }
}
