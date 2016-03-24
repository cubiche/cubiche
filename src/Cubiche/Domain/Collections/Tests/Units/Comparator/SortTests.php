<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\Comparator;

use Cubiche\Domain\Collections\Comparator\SelectorComparator;
use Cubiche\Domain\Collections\Comparator\Sort;
use Cubiche\Domain\Collections\Tests\Units\TestCase;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\Custom;
use Cubiche\Domain\Specification\Criteria;

/**
 * SortTests class.
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
                return $a - $b > 0;
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
            ->given($comparator = Sort::by(Criteria::property('age')))
            ->then()
                ->object($comparator)
                    ->isInstanceOf(SelectorComparator::class)
        ;
    }
}
