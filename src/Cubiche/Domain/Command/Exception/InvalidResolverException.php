<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Exception;

use InvalidArgumentException;
use Exception;

/**
 * InvalidResolverException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidResolverException extends InvalidArgumentException
{
    /**
     * Creates an exception for an invalid resolver.
     *
     * @param mixed          $resolver
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return InvalidResolverException
     */
    public static function forResolver($resolver, $type, Exception $cause = null)
    {
        return new static(sprintf(
            'Expected a resolver that implement the %s interface. Got: %s',
            $type,
            is_object($resolver) ? get_class($resolver) : gettype($resolver)
        ), 0, $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return InvalidResolverException
     */
    public static function forClassName($command, Exception $cause = null)
    {
        return self::forCommand($command, 'className', $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return InvalidResolverException
     */
    public static function forMethodName($command, Exception $cause = null)
    {
        return self::forCommand($command, 'methodName', $cause);
    }

    /**
     * @param mixed          $command
     * @param Exception|null $cause
     *
     * @return InvalidResolverException
     */
    public static function forHandler($command, Exception $cause = null)
    {
        return self::forCommand($command, 'handler', $cause);
    }
}
