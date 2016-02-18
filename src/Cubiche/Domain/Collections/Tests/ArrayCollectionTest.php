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

use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Model\Tests\TestCase;
use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Collections\Specification\Criteria;

/**
 * Array Collection Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ArrayCollectionTest extends TestCase
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
        $search = $collection->find(Criteria::gt(8));

        $this->assertEquals($search->count(), 2);
        $this->assertEquals($search->toArray(), array(9, 10));
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
        $search = $collection->find(Criteria::gt(3))->slice(2, 4);

        $this->assertEquals($search->count(), 4);
        $this->assertEquals($search->toArray(), array(6, 7, 8, 9));
    }

    /**
     * @return number[][][]
     */
    public function provideItems()
    {
        return array(
            array(
                array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            ),
        );
    }
}
