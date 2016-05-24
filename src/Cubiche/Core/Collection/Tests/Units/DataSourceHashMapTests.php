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

use Cubiche\Core\Collection\DataSource\ArrayDataSource;
use Cubiche\Core\Collection\DataSourceHashMap;
use Cubiche\Core\Specification\Criteria;

/**
 * DataSourceHashMapTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DataSourceHashMapTests extends HashMapTestCase
{
    use DataSourceCollectionTestCase;

    /**
     * {@inheritdoc}
     */
    protected function randomCollection($size = null)
    {
        return new DataSourceHashMap(new ArrayDataSource($this->randomValues($size)));
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new DataSourceHashMap(new ArrayDataSource([]));
    }

    /**
     * Test find.
     */
    public function testFind()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $criteria = Criteria::same($unique),
                $emptyCollection = $this->emptyCollection()
            )
            ->when($findResult = $emptyCollection->find($criteria))
            ->then()
                ->hashmap($findResult)
                ->isEmpty()
            ->and()
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
            ->when($findResult = $randomCollection->find($criteria))
            ->then()
                ->hashmap($findResult)
                    ->isEmpty()
            ->and()
            ->when($randomCollection->set('bar', $unique))
            ->and($findResult = $randomCollection->find($criteria))
            ->then()
                ->array($findResult->toArray())
                    ->contains($unique)
        ;
    }
}
