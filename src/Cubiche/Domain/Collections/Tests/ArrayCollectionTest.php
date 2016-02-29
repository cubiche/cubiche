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

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Comparable\Comparator;

/**
 * Array Collection Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ArrayCollectionTest extends CollectionTestCase
{
    /**
     * @param array $items
     *
     * @test
     * @dataProvider provideItems
     */
    public function testFind(array $items)
    {
        $collection = new ArrayCollection($items);
        $this->findTest($collection, Criteria::gt(8));
    }

    /**
     * @param array $items
     *
     * @test
     * @dataProvider provideItems
     */
    public function testFindAndSlice(array $items)
    {
        $collection = new ArrayCollection($items);

        $this->findAndSliceTest($collection, Criteria::gt(3), 2, 4);
        $this->findAndSliceTest($collection, Criteria::gt(3), 0, 5);
        $this->findAndSliceTest($collection, Criteria::gt(3), 4, 4);
        $this->findAndSliceTest($collection, Criteria::gt(3), 8, 4);
    }

    /**
     * @param array $items
     *
     * @test
     * @dataProvider provideItems
     */
    public function testSort(array $items)
    {
        $collection = new ArrayCollection($items);
        $collection->sort();
        $this->assertSorted($collection, new Comparator());
    }

    /**
     * @param array $items
     *
     * @test
     * @dataProvider provideItems
     */
    public function testSorted(array $items)
    {
        $comparator = new Comparator();
        $collection = new ArrayCollection($items);
        $this->assertSorted($collection->sorted($comparator), $comparator);
    }

    /**
     * @return number[][][]
     */
    public function provideItems()
    {
        return array(
            array(
                array(8, 7, 1, 2, 3, 10, 9, 5, 4, 6),
            ),
        );
    }
}
