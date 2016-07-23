<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Equatable;

/**
 * Equality Comparer class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class EqualityComparer extends AbstractEqualityComparer
{
    /**
     * @var EqualityComparerInterface
     */
    private static $defaultComparer = null;

    /**
     * @return \Cubiche\Core\Equatable\EqualityComparerInterface
     */
    public static function defaultComparer()
    {
        if (self::$defaultComparer === null) {
            self::$defaultComparer = new self();
        }

        return self::$defaultComparer;
    }

    /**
     * @param callable $equalityComparator
     *
     * @return \Cubiche\Core\Equatable\EqualityComparerInterface
     */
    public static function from(callable $equalityComparer)
    {
        if ($equalityComparer instanceof EqualityComparerInterface) {
            return $equalityComparer;
        }

        return new CustomEqualityComparer($equalityComparer);
    }

    /**
     * @param callable $equalityComparer
     * @param callable $default
     *
     * @return \Cubiche\Core\Equatable\EqualityComparerInterface
     */
    public static function ensure(callable $equalityComparer = null, callable $default = null)
    {
        return $equalityComparer !== null || $default !== null ?
            self::from($equalityComparer !== null ? $equalityComparer : $default) : self::defaultComparer();
    }
}
