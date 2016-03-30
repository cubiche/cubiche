<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable\Tests\Units;

use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ReverseComparator;

/**
 * ReverseComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ReverseComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::comparator()
     */
    protected function comparator()
    {
        $comparator = new Comparator();

        return new ReverseComparator($comparator->reverse());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitReverseComparator';
    }
}
