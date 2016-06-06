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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;
use Cubiche\Core\Collections\Tests\Units\HashMapTestCase;
use Cubiche\Core\Equatable\Tests\Fixtures\EquatableObject;
use Cubiche\Core\Specification\Criteria;

/**
 * ArrayHashMapTests class.
 *
 * @method protected ArrayHashMapInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayHashMapTests extends HashMapTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return new EquatableObject(\rand(0, 100));
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new EquatableObject(1000);
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->then()
                ->hashmap($collection)
                    ->isInstanceOf(ArrayHashMapInterface::class)
        ;
    }

    /*
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->and($unique = $this->uniqueValue())
            ->then()
                ->variable($collection->get('foo'))
                    ->isNull()
            ->and()
            ->when($collection->set('foo', $unique))
            ->then()
                ->variable($collection->get('foo'))
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test containsValue.
     */
    public function testContainsValue()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then()
                ->boolean($collection->containsValue($unique))
                    ->isFalse()
            ->and()
            ->when($collection->set('bar', $unique))
            ->then()
                ->boolean($collection->containsValue($unique))
                    ->isTrue()
        ;
    }

    /*
     * Test keys.
     */
    public function testKeys()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->then()
                ->variable($collection->keys()->findOne(Criteria::eq('foo')))
                    ->isNull()
                ->variable($collection->keys()->findOne(Criteria::eq('bar')))
                    ->isNull()
            ->and()
            ->when($collection->set('foo', 12))
            ->and($collection->set('bar', 'baz'))
            ->then()
                ->variable($collection->keys()->findOne(Criteria::eq('foo')))
                    ->isNotNull()
                ->variable($collection->keys()->findOne(Criteria::eq('bar')))
                    ->isNotNull()
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
            ->when($collection->sort())
            ->then()
                ->hashmap($collection)
                    ->isSortedUsing($comparator)
            ->and
            ->when($collection->sort($reverseComparator))
            ->then()
                ->hashmap($collection)
                    ->isSortedUsing($reverseComparator)
                ->hashmap($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }

    /*
     * Test offsetUnset.
     */
    public function testOffsetUnset()
    {
        $this
            ->given(
                $key = 0,
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->when($collection[$key] = $unique)
            ->then()
                ->variable($collection[$key])
                    ->isEqualTo($unique)
            ->and()
            ->when(function () use ($collection, $key) {
                unset($collection[$key]);
            })
            ->then()
                ->variable($collection[$key])
                    ->isNull()
        ;
    }
}
