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

use Cubiche\Core\Collection\ArrayCollection\ArraySet;
use Cubiche\Core\Collection\ArrayCollection\ArraySetInterface;
use Cubiche\Core\Collection\Exception\InvalidKeyException;
use Cubiche\Core\Collection\Tests\Units\SetTestCase;
use Cubiche\Core\Equatable\Tests\Fixtures\EquatableObject;

/**
 * ArraySetTests class.
 *
 * @method protected ArraySetInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArraySetTests extends SetTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new ArraySet();
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
                ->set($collection)
                    ->isInstanceOf(ArraySetInterface::class)
        ;
    }

    /*
     * Test contains.
     */
    public function testContains()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then()
                ->boolean($collection->contains($unique))
                    ->isFalse()
            ->and
            ->when($collection->add($unique))
            ->then()
                ->boolean($collection->contains($unique))
                    ->isTrue()
        ;
    }

    /*
     * Test containsAll.
     */
    public function testContainsAll()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $other = 'foo',
                $collection = $this->randomCollection()
            )
            ->then()
                ->boolean($collection->contains($unique))
                    ->isFalse()
                ->boolean($collection->contains($other))
                    ->isFalse()
            ->and
            ->when($collection->addAll([$unique, $other]))
            ->then()
                ->boolean($collection->containsAll([$unique, $other]))
                    ->isTrue()
                ->boolean($collection->containsAll([$unique, 'hjgasd756']))
                    ->isFalse()
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
                ->set($collection)
                    ->isSortedUsing($comparator)
            ->and
            ->when($collection->sort($reverseComparator))
            ->then()
                ->set($collection)
                    ->isSortedUsing($reverseComparator)
                ->set($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }

    /*
     * Test offsetExists.
     */
    public function testOffsetExists()
    {
        $this
            ->given(
                $key = 0,
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->then()
                ->boolean(isset($collection[$key]))
                    ->isFalse()
            ->and
            ->when($collection->add($unique))
            ->then()
                ->boolean(isset($collection[$key]))
                    ->isTrue()
                ->exception(function () use ($collection) {
                    isset($collection['foo']);
                })->isInstanceOf(InvalidKeyException::class)
        ;
    }

    /*
     * Test offsetGet.
     */
    public function testOffsetGet()
    {
        $this
            ->given(
                $key = 0,
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->then()
                ->variable($collection[$key])
                    ->isNull()
            ->and
            ->when($collection->add($unique))
            ->then()
                ->variable($collection[$key])
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test offsetSet.
     */
    public function testOffsetSet()
    {
        $this
            ->given(
                $key = 5,
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->when($collection[$key] = $unique)
            ->then()
                ->variable($collection[$key])
                    ->isNull()
                ->variable($collection[0])
                    ->isEqualTo($unique)
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
