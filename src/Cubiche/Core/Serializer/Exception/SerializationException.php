<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Exception;

use RuntimeException;
use Exception;

/**
 * SerializationException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SerializationException extends RuntimeException
{
    /**
     * Creates a new exception for an invalid serialization.
     *
     * @param object         $object
     * @param Exception|null $cause
     *
     * @return SerializationException
     */
    public static function forObject($object, Exception $cause = null)
    {
        return new static(sprintf(
            'Invalid serialization for %s object',
            is_object($object) ? get_class($object) : gettype($object)
        ), 0, $cause);
    }
}
