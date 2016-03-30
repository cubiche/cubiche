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

/**
 * CustomTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CustomTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTests::comparator()
     */
    protected function comparator()
    {
        $comparator = new Comparator();

        return new Custom(function ($a, $b) use ($comparator) {
            if (!$a instanceof ComparableInterface && !$b instanceof ComparableInterface) {
                return $comparator->compare($a * 10, $b * 10);
            }

            return $comparator->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Tests\Units\ComparatorTests::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitCustomComparator';
    }
}
