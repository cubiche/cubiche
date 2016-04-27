<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Exception;

use InvalidArgumentException;
use Exception;

/**
 * InvalidCommandException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidCommandException extends InvalidArgumentException
{
    /**
     * Creates an exception for an invalid command.
     *
     * @param mixed          $value
     * @param Exception|null $cause
     *
     * @return InvalidCommandException
     */
    public static function forUnknownValue($value, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a command of type object. Got: %s',
            gettype($value)
        ), 0, $cause);
    }
}
