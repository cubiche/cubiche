<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Exception;

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
     * Creates an exception for a not found commandName/methodName or handler.
     *
     * @param mixed          $command
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    protected static function forCommand($command, $type, Exception $cause = null)
    {
        return new static(sprintf(
            'Not found a %s for a given command of type %s',
            $type,
            is_object($command) ? get_class($command) : gettype($command)
        ), 0, $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function commandNameForCommand($command, Exception $cause = null)
    {
        return self::forCommand($command, 'command name', $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function methodNameForCommand($command, Exception $cause = null)
    {
        return self::forCommand($command, 'method name', $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function handlerForCommand($command, Exception $cause = null)
    {
        return self::forCommand($command, 'handler', $cause);
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
        ), 0, $cause);
    }
}
