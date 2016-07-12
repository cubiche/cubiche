<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

/**
 * Sort class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Sort
{
    /**
     * @var ComparatorInterface
     */
    protected static $comparator = null;

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function comparator()
    {
        if (self::$comparator === null) {
            self::$comparator = new Comparator();
        }

        return self::$comparator;
    }

    /**
     * @param callable $selector
     * @param Order    $order
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function by(callable $selector, Order $order = null)
    {
        return new SelectorComparator($selector, $order);
    }
}
