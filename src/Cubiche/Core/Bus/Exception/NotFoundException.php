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

use RuntimeException;
use Exception;

/**
 * NotFoundException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotFoundException extends RuntimeException
{
    /**
     * Creates an exception for a not found message name, method name or handler for a given message.
     *
     * @param string         $type
     * @param mixed          $message
     * @param int            $code
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    protected static function notFound($type, $message, $code = 0, Exception $cause = null)
    {
        return new static(sprintf(
            'Not found a %s for a given object of type %s',
            $type,
            is_object($message) ? get_class($message) : gettype($message)
        ), $code, $cause);
    }

    /**
     * @param mixed          $object
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function nameOfMessage($object, Exception $cause = null)
    {
        return self::notFound('name of message', $object, 1, $cause);
    }

    /**
     * @param mixed          $object
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function handlerMethodNameForObject($object, Exception $cause = null)
    {
        return self::notFound('handler method name', $object, 3, $cause);
    }

    /**
     * @param string         $messageName
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function handlerFor($messageName, Exception $cause = null)
    {
        return new static(sprintf(
            'Not found a handler for a given message named %s',
            $messageName
        ), 5, $cause);
    }

    /**
     * Creates an exception for a not found middleware.
     *
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function middlewareOfType($type, Exception $cause = null)
    {
        return new static(sprintf(
            'Not found a middleware of type %s',
            $type
        ), 6, $cause);
    }

    /**
     * @param mixed          $object
     * @param string         $method
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function methodForObject($object, $method, Exception $cause = null)
    {
        return self::notFound('method with name `'.$method.'`', $object, 7, $cause);
    }
}
