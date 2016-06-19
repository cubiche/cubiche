<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Units\ArrayCollection;

use Cubiche\Core\Collections\ArrayCollection\SortedArrayHashMap;
use Cubiche\Core\Comparable\Comparator;

/**
 * SortedArrayHashMapTests class.
 *
 * @method protected ArrayHashMapInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayHashMapTests extends ArrayHashMapTests
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new SortedArrayHashMap([], $this->comparator());
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = new Comparator())
            ->and($collection = new SortedArrayHashMap($this->randomValues(10)))
            ->and($reverseComparator = $comparator->reverse())
            ->then()
                ->hashmap($collection)
                    ->isSortedUsing($comparator)
                    ->isNotSortedUsing($reverseComparator)
        ;
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->and($unique = $this->uniqueValue())
            ->and($comparator = $this->comparator())
            ->then()
                ->hashmap($collection)
                    ->notContainsKey('foo')
                    ->isSortedUsing($comparator)
            ->and()
            ->when($collection->set('foo', $unique))
            ->then()
                ->hashmap($collection)
                    ->containsKey('foo')
                    ->isSortedUsing($comparator)
        ;
    }

    /*
     * Test removeAt.
     */
    public function testRemoveAt()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->and($comparator = $this->comparator())
            ->then()
                ->hashmap($collection)
                    ->notContainsKey('foo')
                    ->isSortedUsing($comparator)
            ->and()
            ->when($collection->set('foo', $unique))
            ->then()
                ->hashmap($collection)
                    ->containsKey('foo')
            ->and()
            ->when($element = $collection->removeAt('foo'))
            ->then()
                ->variable($element)
                    ->isEqualTo($unique)
                ->hashmap($collection)
                    ->isSortedUsing($comparator)
        ;
    }

    /*
     * Test sort.
     */
    public function testSort()
    {
        $this
            ->given(
                $comparator = $this->comparator(),
                $reverseComparator = $comparator->reverse(),
                $collection = $this->randomCollection()
            )
            ->when($collection->sort($reverseComparator))
            ->then()
                ->hashmap($collection)
                    ->isSortedUsing($reverseComparator)
            ->and
            ->when($collection->sort())
            ->then()
                ->hashmap($collection)
                    ->isSortedUsing($reverseComparator)
                ->hashmap($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }
}
