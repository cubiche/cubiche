<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Selector\Property;

/**
 * Order class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Order
{
    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function defaultComparator()
    {
        return Comparator::defaultComparator();
    }

    /**
     * @param callable|string $selector
     * @param Direction       $direction
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function by($selector, Direction $direction = null)
    {
        if (\is_string($selector)) {
            $selector = new Property($selector);
        }

        return Comparator::by($selector, $direction);
    }
}
