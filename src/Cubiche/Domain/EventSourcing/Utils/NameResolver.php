<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Utils;

/**
 * NameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NameResolver
{
    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    public static function resolve($aggregateClassName)
    {
        $pieces = explode(' ', trim(preg_replace('([A-Z])', ' $0', self::shortClassName($aggregateClassName))));

        return strtolower(implode('_', $pieces));
    }

    /**
     * @param $aggregateClassName
     *
     * @return string
     */
    protected static function shortClassName($aggregateClassName)
    {
        // If class name has a namespace separator, only take last portion
        if (strpos($aggregateClassName, '\\') !== false) {
            return substr($aggregateClassName, strrpos($aggregateClassName, '\\') + 1);
        }

        return $aggregateClassName;
    }
}
