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

use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Collections\ArrayCollection\ArrayListInterface;
use Cubiche\Core\Collections\Exception\InvalidKeyException;
use Cubiche\Core\Collections\Tests\Units\ListTestCase;
use Cubiche\Core\Equatable\Tests\Fixtures\Value;

/**
 * ArrayListTests class.
 *
 * @method protected ArrayListInterface randomCollection($size = null)
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayListTests extends ListTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new ArrayList();
    }

    /**
     * {@inheritdoc}
     */
    protected function randomValue()
    {
        return new Value(\rand(0, 100));
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new Value(1000);
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->then()
                ->list($collection)
                    ->isInstanceOf(ArrayListInterface::class)
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
     * Test removeAt.
     */
    public function testRemoveAt()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->and($collection->add($unique))
            ->and($key = $collection->indexOf('foo'))
            ->when($element = $collection->removeAt($key))
            ->then()
                ->variable($element)
                    ->isNull()
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->and($collection->add($unique))
            ->and($key = $collection->indexOf($unique))
            ->when($element = $collection->removeAt($key))
            ->then()
                ->boolean($collection->contains($unique))
                    ->isFalse()
                ->variable($element)
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
                $comparator = $this->comparator(),
                $reverseComparator = $comparator->reverse(),
                $collection = $this->randomCollection()
            )
            ->when($collection->sort())
            ->then()
                ->list($collection)
                    ->isSortedUsing($comparator)
            ->and
            ->when($collection->sort($reverseComparator))
            ->then()
                ->list($collection)
                    ->isSortedUsing($reverseComparator)
                ->list($collection)
                    ->isNotSortedUsing($comparator)
        ;
    }

    /*
     * Test validateKey.
     */
    public function testValidateKey()
    {
        $this
            ->given(
                $key = 0,
                $collection = $this->emptyCollection()
            )
            ->then()
                ->boolean(isset($collection[$key]))
                    ->isFalse()
            ->and()
            ->then()
                ->exception(function () use ($collection) {
                    $collection->removeAt('foo');
                })->isInstanceOf(InvalidKeyException::class)
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
