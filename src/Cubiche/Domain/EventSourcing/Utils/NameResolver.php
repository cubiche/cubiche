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

use Cubiche\Domain\Model\IdInterface;

/**
 * NameResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NameResolver
{
    /**
     * @param string      $aggregateClassName
     * @param IdInterface $id
     *
     * @return string
     */
    public static function resolve($aggregateClassName, IdInterface $id)
    {
        return sprintf('%s-%s', self::shortClassName($aggregateClassName), $id->toNative());
    }

    /**
     * @param string $aggregateClassName
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
