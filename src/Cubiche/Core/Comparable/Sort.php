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

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Sort Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Sort
{
    /**
     * @var Comparator
     */
    protected static $comparator = null;

    /**
     * @return \Cubiche\Core\Comparable\Comparator
     */
    public static function comparator()
    {
        if (self::$comparator === null) {
            self::$comparator = new Comparator();
        }

        return self::$comparator;
    }

    /**
     * @param $callable $callable
     *
     * @return \Cubiche\Core\Comparable\Custom
     */
    public static function custom(callable $callable)
    {
        return new Custom($callable);
    }

    /**
     * @param SelectorInterface $selector
     * @param Order             $order
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function by(SelectorInterface $selector, Order $order = null)
    {
        return new SelectorComparator($selector, $order === null ? Order::ASC() : $order);
    }
}
