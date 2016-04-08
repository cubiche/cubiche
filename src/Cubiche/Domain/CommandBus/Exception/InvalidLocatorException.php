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

use Cubiche\Domain\CommandBus\Middlewares\Handler\Locator\LocatorInterface;
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
     * @param mixed          $locator
     * @param Exception|null $cause
     *
     * @return InvalidLocatorException
     */
    public static function forUnknownValue($locator, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a locator that implement the %s interface. Got: %s',
            LocatorInterface::class,
            is_object($locator) ? get_class($locator) : gettype($locator)
        ), 0, $cause);
    }
}
