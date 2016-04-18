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

use Cubiche\Core\Comparable\Sort;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Custom;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Comparable\SelectorComparator;
use Cubiche\Core\Comparable\Order;

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
                    ->isInstanceOf(Comparator::class)
        ;
    }

    /**
     * Test custom comparator.
     */
    public function testCustomComparator()
    {
        $this
            ->given($comparator = Sort::custom(function ($a, $b) {
                return 0;
            }))
            ->then()
                ->object($comparator)
                    ->isInstanceOf(Custom::class)
        ;
    }

    /**
     * Test by.
     */
    public function testBy()
    {
        $this
            ->given($property = new Property('foo'))
            /* @var \Cubiche\Core\Comparable\SelectorComparator $comparator */
            ->when($comparator = Sort::by($property))
            ->then()
                ->object($comparator)
                    ->isInstanceOf(SelectorComparator::class)
                ->object($comparator->selector())
                    ->isIdenticalTo($property)
                ->object($comparator->order())
                    ->isEqualTo(Order::ASC())
            ->when($comparator = Sort::by($property, Order::DESC()))
            ->then()
                ->object($comparator->order())
                    ->isEqualTo(Order::DESC())
        ;
    }
}
