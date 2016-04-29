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

use Cubiche\Core\CommandBus\Middlewares\Handler\Locator\LocatorInterface;
use InvalidArgumentException;
use Exception;

/**
 * InvalidLocatorException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidLocatorException extends InvalidArgumentException
{
    /**
     * Creates an exception for an invalid locator.
     *
     * @param mixed          $value
     * @param Exception|null $cause
     *
     * @return InvalidLocatorException
     */
    public static function forUnknownValue($value, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a locator of type %s. Got: %s',
            LocatorInterface::class,
            is_object($value) ? get_class($value) : gettype($value)
        ), 0, $cause);
    }
}
