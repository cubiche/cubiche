<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Exception;

use Cubiche\Core\Serializer\SerializableInterface;
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
     * @param object         $object
     * @param Exception|null $cause
     *
     * @return SerializationException
     */
    public static function invalidObject($object, Exception $cause = null)
    {
        return new static(sprintf(
            'The object %s must be an instance of %s.',
            is_object($object) ? get_class($object) : gettype($object),
            SerializableInterface::class
        ), 0, $cause);
    }

    /**
     * @param string         $className
     * @param Exception|null $cause
     *
     * @return SerializationException
     */
    public static function invalidClass($className, Exception $cause = null)
    {
        return new static(sprintf(
            'The class %s must be an instance of %s.',
            $className,
            SerializableInterface::class
        ), 0, $cause);
    }

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

    /**
     * @param string         $propertyName
     * @param string         $className
     * @param Exception|null $cause
     *
     * @return SerializationException
     */
    public static function propertyNotFound($propertyName, $className, Exception $cause = null)
    {
        return new static(sprintf(
            'Property `%s` not found for object %s.',
            $propertyName,
            $className
        ), 0, $cause);
    }
}
