<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units;

use Cubiche\Core\Collection\ListInterface;
use Cubiche\Core\Specification\Criteria;

/**
 * ListTestCase class.
 *
 * @method protected ListInterface emptyCollection()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ListTestCase extends CollectionTestCase
{
    /**
     * @param int $size
     *
     * @return ListInterface
     */
    protected function randomCollection($size = null)
    {
        $collection = $this->emptyCollection();
        $collection->addAll($this->randomValues($size));

        return $collection;
    }

    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(ListInterface::class)
        ;
    }

    /**
     * Test add.
     */
    public function testAdd()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->given($unique = $this->uniqueValue())
            ->let($count = $collection->count())
            ->when($collection->add($unique))
                ->list($collection)
                    ->contains($unique)
                    ->size()
                        ->isEqualTo($count + 1)
        ;
    }

    /**
     * Test add.
     */
    public function testAddAll()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->given($items = $this->randomValues(10))
            ->when($collection->addAll($items))
                ->list($collection)
                    ->containsValues($items)
                    ->size()
                        ->isEqualTo(\count($items))
            ->and()
            ->then()
                ->exception(function () use ($collection) {
                    $collection->addAll(100);
                })->isInstanceOf(\InvalidArgumentException::class)
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
            ->when($emptyCollection->add($unique))
            ->then()
                ->list($emptyCollection)
                    ->contains($unique)
            ->and()
            ->when($result = $emptyCollection->remove($unique))
            ->then()
                ->list($emptyCollection)
                    ->notContains($unique)
                ->boolean($result)
                    ->isTrue()
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $randomCollection = $this->randomCollection()
            )
            ->when($randomCollection->add($unique))
            ->then()
                ->list($randomCollection)
                    ->contains($unique)
            ->and()
            ->when($randomCollection->remove($unique))
            ->then()
                ->list($randomCollection)
                    ->notContains($unique)
            ->and()
            ->when($result = $randomCollection->remove('foo'))
            ->then()
                ->boolean($result)
                    ->isFalse()
        ;
    }

    /**
     * Test find.
     */
    public function testFind()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($findResult = $emptyCollection->find($criteria))
            ->then()
                ->list($findResult)
                    ->isEmpty()
            ->and()
            ->when(
                $emptyCollection->add($unique),
                $findResult = $emptyCollection->find($criteria)
            )
            ->then()
                ->list($findResult)
                    ->contains($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $randomCollection = $this->randomCollection()
            )
            ->when($findResult = $randomCollection->find($criteria))
            ->then()
                ->list($findResult)
                    ->isEmpty()
            ->and()
            ->when(
                $randomCollection->add($unique),
                $findResult = $randomCollection->find($criteria)
            )
            ->then()
                ->list($findResult)
                    ->contains($unique)
        ;
    }

    /**
     * Test findOne.
     */
    public function testFindOne()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($findResult = $emptyCollection->findOne($criteria))
            ->then()
                ->variable($findResult)
                    ->isNull()
            ->and()
            ->when(
                $emptyCollection->add($unique),
                $findResult = $emptyCollection->findOne($criteria)
            )
            ->then()
                ->variable($findResult)
                    ->isEqualTo($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::eq($unique),
                $randomCollection = $this->randomCollection()
            )
            ->when($findResult = $randomCollection->findOne($criteria))
            ->then()
                ->variable($findResult)
                    ->isNull()
            ->and()
            ->when(
                $randomCollection->add($unique),
                $findResult = $randomCollection->findOne($criteria)
            )
            ->then()
                ->variable($findResult)
                    ->isEqualTo($unique)
        ;
    }

    /**
     * Test subList.
     */
    public function testSubList()
    {
        $this
            ->given($collection = $this->randomCollection())
            ->let($count = $collection->count())
            ->let($offset = rand(0, $count / 2))
            ->let($length = rand($count / 2, $count))
            ->let($maxCount = max([$count - $offset, 0]))
            ->when($slice = $collection->subList($offset, $length))
            ->then()
                ->list($slice)
                    ->size()
                        ->isEqualTo(min($maxCount, $length))
        ;
    }
}
