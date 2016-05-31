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
            ->and($unique = $this->uniqueValue())
            ->and($count = $collection->count())
            ->when($collection->add($unique))
            ->then()
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
            ->and($items = $this->randomValues(10))
            ->when($collection->addAll($items))
            ->then()
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
            ->and($collection->addAll([$unique, $random]))
            ->then()
                ->list($collection)
                    ->contains($unique)
                ->list($collection)
                    ->contains($random)
            ->and()
            ->when($collection->removeAll([$unique, $random]))
            ->then()
                ->boolean($collection->isEmpty())
                    ->isTrue()
        ;
    }

    /**
     * Test find.
     */
    public function testFind()
    {
        parent::testFind();

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::same($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($emptyCollection->add($unique))
            ->and($findResult = $emptyCollection->find($criteria))
            ->then()
                ->list($findResult)
                    ->size()
                        ->isEqualTo(1)
                ->array($findResult->toArray())
                    ->contains($unique)
        ;

        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::same($unique),
                $randomCollection = $this->randomCollection()
            )
            ->when($randomCollection->add($unique))
            ->and($findResult = $randomCollection->find($criteria))
            ->then()
                ->array($findResult->toArray())
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
}
