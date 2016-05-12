<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Exception;

use Exception;
use InvalidArgumentException;

/**
 * InvalidResolverException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidResolverException extends InvalidArgumentException
{
    /**
     * Creates an exception for an invalid locator.
     *
     * @param mixed          $value
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return InvalidLocatorException
     */
    public static function forUnknownValue($value, $type, Exception $cause = null)
    {
        return new static(sprintf(
            'The object must be an instance of %s. Instance of %s given',
            $type,
            is_object($value) ? get_class($value) : gettype($value)
        ), 0, $cause);
    }
}
