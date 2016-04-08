<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Exception;

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
     * Creates an exception for a not found className/methodName or handler.
     *
     * @param mixed          $command
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function forCommand($command, $type, Exception $cause = null)
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
    public static function classNameForCommand($command, Exception $cause = null)
    {
        return self::forCommand($command, 'className', $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function methodNameForCommand($command, Exception $cause = null)
    {
        return self::forCommand($command, 'methodName', $cause);
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
}
