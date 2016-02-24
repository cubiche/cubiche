<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests;

use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Collection Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class CollectionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param CollectionInterface    $collection
     * @param SpecificationInterface $criteria
     */
    protected function findTest(CollectionInterface $collection, SpecificationInterface $criteria)
    {
        $count = $collection->count();
        $found = $collection->find($criteria);
        $foundCount = $found->count();
        $this->assertAllTrue($found, $criteria);

        $notFound = $collection->find($criteria->not());
        $notFoundCount = $notFound->count();
        $this->assertAllFalse($notFound, $criteria);

        $this->assertEquals($count, $foundCount + $notFoundCount);
    }

    /**
     * @param CollectionInterface    $collection
     * @param SpecificationInterface $criteria
     * @param int                    $offset
     * @param int                    $length
     */
    protected function findAndSliceTest(
        CollectionInterface $collection,
        SpecificationInterface $criteria,
        $offset,
        $length = null
    ) {
        $found = $collection->find($criteria);
        $foundCount = $found->count();

        $slice = $collection->find($criteria)->slice($offset, $length);

        $maxCount = \max([$foundCount - $offset, 0]);
        $this->assertEquals($length === null ? $maxCount : \min([$maxCount, $length]), $slice->count());

        $this->assertAllTrue($slice, $criteria);
    }

    /**
     * @param CollectionInterface    $collection
     * @param SpecificationInterface $criteria
     */
    protected function assertAllTrue(CollectionInterface $collection, SpecificationInterface $criteria)
    {
        foreach ($collection as $item) {
            $this->assertTrue($criteria->evaluate($item));
        }
    }

    /**
     * @param CollectionInterface    $collection
     * @param SpecificationInterface $criteria
     */
    protected function assertAllFalse(CollectionInterface $collection, SpecificationInterface $criteria)
    {
        foreach ($collection as $item) {
            $this->assertFalse($criteria->evaluate($item));
        }
    }

    /**
     * @param CollectionInterface $collection
     * @param ComparatorInterface $comparator
     */
    protected function assertSorted(CollectionInterface $collection, ComparatorInterface $comparator)
    {
        $last = null;
        foreach ($collection as $item) {
            if ($last !== null) {
                $this->assertTrue($comparator->compare($last, $item) <= 0);
            }
            $last = $item;
        }
    }
}
