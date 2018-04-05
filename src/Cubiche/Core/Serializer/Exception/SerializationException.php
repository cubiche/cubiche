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

use Exception;
use RuntimeException;

/**
 * SerializationException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SerializationException extends RuntimeException
{
    /**
     * @param string         $className
     * @param Exception|null $cause
     *
     * @return SerializationException
     */
    public static function invalidMapping($className, Exception $cause = null)
    {
        return new static(sprintf(
            'There is not serializer mapping file for class %s.',
            $className
        ), 0, $cause);
    }
}
