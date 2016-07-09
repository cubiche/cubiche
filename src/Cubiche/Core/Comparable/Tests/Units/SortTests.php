<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Units;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Comparable\Order;
use Cubiche\Core\Comparable\SelectorComparator;
use Cubiche\Core\Comparable\Sort;

/**
 * Sort Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortTests extends TestCase
{
    /**
     * Test default comparator.
     */
    public function testComparator()
    {
        $this
            ->given($comparator = Sort::comparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ComparatorInterface::class)
        ;
    }

    /**
     * Test by.
     */
    public function testBy()
    {
        $this
            ->given($selector = function ($value) {
                return $value['foo'];
            })
            /* @var \Cubiche\Core\Comparable\SelectorComparator $comparator */
            ->when($comparator = Sort::by($selector))
            ->then()
                ->object($comparator)
                    ->isInstanceOf(SelectorComparator::class)
                ->object($comparator->selector())
                    ->isIdenticalTo($selector)
                ->object($comparator->order())
                    ->isEqualTo(Order::ASC())
            ->when($comparator = Sort::by($selector, Order::DESC()))
            ->then()
                ->object($comparator->order())
                    ->isEqualTo(Order::DESC())
        ;
    }
}
