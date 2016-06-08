<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Units;

use Cubiche\Core\Collections\SetInterface;
use Cubiche\Core\Specification\Criteria;

/**
 * SetTestCase class.
 *
 * @method protected SetInterface emptyCollection()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SetTestCase extends CollectionTestCase
{
    /**
     * @param int $size
     *
     * @return SetInterface
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
                ->implements(SetInterface::class)
        ;
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->then()
                ->integer($collection->count())
                    ->isEqualTo(0)
            ->and()
            ->when($collection->add($this->uniqueValue()))
            ->then()
                ->integer($collection->count())
                    ->isEqualTo(1)
        ;
    }

    /**
     * Test add.
     */
    public function testAdd()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->and($unique = $this->uniqueValue())
            ->when($collection->add($unique))
            ->and($collection->add($unique))
            ->then()
                ->set($collection)
                    ->contains($unique)
                    ->size()
                        ->isEqualTo(1)
        ;
    }

    /**
     * Test add.
     */
    public function testAddAll()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->and($items = [1, 2, 3, 4, 5, 6])
            ->when($collection->addAll($items))
            ->and($collection->addAll($items))
            ->then()
                ->set($collection)
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
                ->set($emptyCollection)
                    ->contains($unique)
            ->and()
            ->when($result = $emptyCollection->remove($unique))
            ->then()
                ->set($emptyCollection)
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
                ->set($randomCollection)
                    ->contains($unique)
            ->and()
            ->when($randomCollection->remove($unique))
            ->then()
                ->set($randomCollection)
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
                ->set($collection)
                    ->contains($unique)
                ->set($collection)
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
                ->set($findResult)
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
