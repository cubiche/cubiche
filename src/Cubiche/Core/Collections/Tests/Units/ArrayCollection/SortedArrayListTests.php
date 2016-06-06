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

use Cubiche\Core\Collections\ArrayCollection\SortedArrayList;
use Cubiche\Core\Comparable\Comparator;

/**
 * SortedArrayListTests class.
 *
 * @method protected ArrayListInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayListTests extends ArrayListTests
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new SortedArrayList([], $this->comparator());
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = new Comparator())
            ->and($collection = new SortedArrayList($this->randomValues(10)))
            ->and($reverseComparator = $comparator->reverse())
            ->then()
                ->list($collection)
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
                ->list($emptyCollection)
                    ->contains($unique)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($result = $emptyCollection->remove($unique))
            ->then()
                ->list($emptyCollection)
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
                ->list($randomCollection)
                    ->contains($unique)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($randomCollection->remove($unique))
            ->then()
                ->list($randomCollection)
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
                ->list($collection)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($collection->removeAll([$unique, $random]))
            ->then()
                ->boolean($collection->isEmpty())
                    ->isTrue()
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
                $collection = $this->randomCollection()
            )
            ->and($comparator = $this->comparator())
            ->when($collection->add($unique))
            ->then()
                ->list($collection)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($element = $collection->removeAt($collection->indexOf('foo')))
            ->then()
                ->variable($element)
                    ->isNull()
                ->list($collection)
                    ->isSortedUsing($comparator)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->and($comparator = $this->comparator())
            ->when($collection->add($unique))
            ->then()
                ->list($collection)
                    ->isSortedUsing($comparator)
            ->and()
            ->when($element = $collection->removeAt($collection->indexOf($unique)))
            ->then()
                ->boolean($collection->contains($unique))
                    ->isFalse()
                ->variable($element)
                    ->isEqualTo($unique)
                ->list($collection)
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
                ->list($collection)
                    ->isSortedUsing($reverseComparator)
            ->and
            ->when($collection->sort())
            ->then()
                ->list($collection)
                    ->isSortedUsing($reverseComparator)
                ->list($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }
}
