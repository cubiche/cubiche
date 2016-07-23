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
use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Comparable\SelectorComparator;

/**
 * Comparator Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ComparatorTests extends ComparatorTestCase
{
    /**
     * Test default comparator method.
     */
    public function testDefaultComparator()
    {
        $this
        ->given($comparator = Comparator::defaultComparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ComparatorInterface::class)
        ;
    }

    /**
     * Test by method.
     */
    public function testBy()
    {
        $this
        ->given($selector = function ($value) {
            return $value['foo'];
        })
        /* @var \Cubiche\Core\Comparable\SelectorComparator $comparator */
        ->when($comparator = Comparator::by($selector))
        ->then()
            ->object($comparator)
                ->isInstanceOf(SelectorComparator::class)
            ->object($comparator->selector())
                ->isIdenticalTo($selector)
            ->object($comparator->direction())
                ->isEqualTo(Direction::ASC())
        ->when($comparator = Comparator::by($selector, Direction::DESC()))
        ->then()
            ->object($comparator->direction())
                ->isEqualTo(Direction::DESC())
        ;
    }
}
