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

use Cubiche\Core\Collection\Exception\InvalidKeyException;
use Cubiche\Core\Collection\HashMapInterface;
use Cubiche\Core\Specification\Criteria;

/**
 * HashMapTestCase class.
 *
 * @method protected HashMapInterface emptyCollection()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class HashMapTestCase extends CollectionTestCase
{
    /**
     * @param int $size
     *
     * @return HashMapInterface
     */
    protected function randomCollection($size = null)
    {
        $collection = $this->emptyCollection();
        foreach ($this->randomValues($size) as $key => $randomValue) {
            $collection->set($key, $randomValue);
        }

        return $collection;
    }

    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(HashMapInterface::class)
        ;
    }

    /**
     * Test set.
     */
    public function testSet()
    {
        $this
            ->given($collection = $this->emptyCollection())
            ->and($unique = $this->uniqueValue())
            ->then()
                ->hashmap($collection)
                    ->notContainsKey('foo')
            ->and()
            ->when($collection->set('foo', $unique))
            ->then()
                ->hashmap($collection)
                    ->containsKey('foo')
                ->exception(function () use ($collection) {
                    $collection->set(new \StdClass(), 'value');
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
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->then()
                ->hashmap($collection)
                    ->notContainsKey('foo')
            ->and()
            ->when($collection->set('foo', $unique))
            ->then()
                ->hashmap($collection)
                    ->containsKey('foo')
            ->and()
            ->when($element = $collection->removeAt('bar'))
            ->then()
                ->variable($element)
                    ->isNull()
            ->and()
            ->when($element = $collection->removeAt('foo'))
            ->then()
                ->variable($element)
                    ->isEqualTo($unique)
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
            ->when($emptyCollection->set('foo', $unique))
            ->and($findResult = $emptyCollection->find($criteria))
            ->then()
                ->hashmap($findResult)
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
            ->when($randomCollection->set('bar', $unique))
            ->and($findResult = $randomCollection->find($criteria))
            ->then()
                ->array($findResult->toArray())
                    ->contains($unique)
        ;
    }
}
