<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units\ArrayCollection;

use Cubiche\Core\Collection\ArrayCollection\SortedArraySet;
use Cubiche\Core\Comparable\Comparator;

/**
 * SortedArraySetTests class.
 *
 * @method protected ArraySetInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArraySetTests extends ArraySetTests
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new SortedArraySet([], $this->comparator());
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = new Comparator())
            ->and($collection = new SortedArraySet($this->randomValues(10)))
            ->and($reverseComparator = $comparator->reverse())
            ->then()
                ->set($collection)
                    ->isSortedUsing($comparator)
                    ->isNotSortedUsing($reverseComparator)
        ;
    }

    /**
     * Test remove.
     */
    public function testRemove()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $emptyCollection = $this->emptyCollection()
            )
            ->and($comparator = $this->comparator())
            ->when($emptyCollection->add($unique))
            ->then()
                ->set($emptyCollection)
                    ->contains($unique)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($result = $emptyCollection->remove($unique))
            ->then()
                ->set($emptyCollection)
                    ->notContains($unique)
                    ->isSortedUsing($comparator)
                ->boolean($result)
                    ->isTrue()
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $randomCollection = $this->randomCollection()
            )
            ->and($comparator = $this->comparator())
            ->when($randomCollection->add($unique))
            ->then()
                ->set($randomCollection)
                    ->contains($unique)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($randomCollection->remove($unique))
            ->then()
                ->set($randomCollection)
                    ->notContains($unique)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($result = $randomCollection->remove('foo'))
            ->then()
                ->boolean($result)
                    ->isFalse()
        ;
    }

    /*
     * Test removeAll.
     */
    public function testRemoveAll()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $random = $this->randomValue(),
                $collection = $this->emptyCollection()
            )
            ->and($comparator = $this->comparator())
            ->and($collection->addAll([$unique, $random]))
            ->then()
                ->boolean($collection->contains($unique))
                    ->isTrue()
                ->boolean($collection->contains($random))
                    ->isTrue()
                ->set($collection)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($collection->removeAll([$unique, $random]))
            ->then()
                ->boolean($collection->isEmpty())
                    ->isTrue()
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
                ->set($collection)
                    ->isSortedUsing($reverseComparator)
            ->and
            ->when($collection->sort())
            ->then()
                ->set($collection)
                    ->isSortedUsing($reverseComparator)
                ->set($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }
}
