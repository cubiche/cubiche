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

use Cubiche\Domain\Comparable\ComparableInterface;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\Custom;
use Cubiche\Domain\Comparable\MultiComparator;

/**
 * MultiComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MultiComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::comparator()
     */
    protected function comparator()
    {
        $comparator = new Comparator();
        $custom = new Custom(function ($a, $b) use ($comparator) {
            if (!$a instanceof ComparableInterface && !$b instanceof ComparableInterface) {
                return $comparator->compare($a * 10, $b * 10);
            }

            return $comparator->compare($a, $b);
        });

        return new MultiComparator($comparator, $custom);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitMultiComparator';
    }
}
