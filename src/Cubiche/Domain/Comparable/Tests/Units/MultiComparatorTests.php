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
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Comparable\Custom;
use Cubiche\Domain\Comparable\MultiComparator;

/**
 * MultiComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MultiComparatorTests extends ComparatorTests
{
    /**
     * @return ComparatorInterface
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
     * @return string
     */
    protected function shouldVisitMethod()
    {
        return 'visitMultiComparator';
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(MultiComparator::class)
        ;
    }
}
