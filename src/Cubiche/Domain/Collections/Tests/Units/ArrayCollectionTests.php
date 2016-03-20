<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units;

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Collections\ArrayCollectionInterface;
use Cubiche\Domain\Collections\Exception\InvalidKeyException;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\Custom;
use Cubiche\Domain\Equatable\Tests\Fixtures\EquatableObject;

/**
 * ArrayCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayCollectionTests extends CollectionTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::emptyCollection()
     */
    protected function emptyCollection()
    {
        return new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::randomValue()
     */
    protected function randomValue()
    {
        return new EquatableObject(\rand(0, 100));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::uniqueValue()
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
            ->then
                ->collection($collection)
                    ->isInstanceOf(ArrayCollectionInterface::class)
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
            ->then
                ->boolean($collection->contains($unique))
                    ->isFalse()
            ->and
            ->when($collection->add($unique))
            ->then
                ->boolean($collection->contains($unique))
                    ->isTrue()
        ;
    }

    /*
     * Test containsKey/set.
     */
    public function testContainsKey()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->boolean($collection->containsKey($key))
                    ->isFalse()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isTrue()
            ->and
            ->exception(function () use ($collection, $unique) {
                $collection->set($unique, $unique);
            })->isInstanceOf(InvalidKeyException::class)
        ;
    }

    /*
     * Test removeAt.
     */
    public function testRemoveAt()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->when($collection->set($key, $unique))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isTrue()
            ->and
            ->when($collection->removeAt($key))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isFalse()
        ;
    }

    /*
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->variable($collection->get($key))
                    ->isNull()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->variable($collection->containsKey($key))
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test sort.
     */
    public function testSort()
    {
        $this
            ->given(
                $comparator = new Comparator(),
                $reverseComparator = new Custom(function ($a, $b) use ($comparator) {
                    return -1 * $comparator->compare($a, $b);
                }),
                $collection = $this->randomCollection()
            )
            ->when($collection->sort())
            ->then
                ->collection($collection)
                    ->isSortedUsing($comparator)
            ->and
            ->when($collection->sort($reverseComparator))
            ->then
                ->collection($collection)
                    ->isSortedUsing($reverseComparator)
                ->collection($collection)
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
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->boolean(isset($collection[$key]))
                    ->isFalse()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->boolean(isset($collection[$key]))
                    ->isTrue()
        ;
    }

    /*
     * Test offsetGet.
     */
    public function testOffsetGet()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->variable($collection[$key])
                    ->isNull()
            ->and
            ->when($collection->set($key, $unique))
            ->then
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
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->when($collection[$key] = $unique)
            ->then
                ->variable($collection[$key])
                    ->isEqualTo($unique)
            ->and
            ->when($collection[] = $key)
            ->then
                ->collection($collection)
                    ->contains($key)
        ;
    }

    /*
     * Test offsetUnset.
     */
    public function testOffsetUnset()
    {
        $this
            ->given(
                $key = 'foo',
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

    /**
     * Test keys.
     */
    public function testKeys()
    {
        $this
            ->given(
                $collection = $this->randomCollection()
            )
            ->when($keys = $collection->keys())
                ->collection($keys)
                   ->array($keys->toArray())
                        ->isEqualTo(\array_keys($collection->toArray()));
    }

    /**
     * Test values.
     */
    public function testValues()
    {
        $this
            ->given(
                $collection = $this->randomCollection()
            )
            ->when($values = $collection->values())
                ->collection($values)
                    ->array($values->toArray())
                        ->isEqualTo(\array_values($collection->toArray()));
    }
}
