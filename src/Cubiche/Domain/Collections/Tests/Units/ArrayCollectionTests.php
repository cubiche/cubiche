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
use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * ArrayCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayCollectionTests extends TestCase
{
    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($collection = new ArrayCollection())
            ->then
                ->collection($collection)
                ->isInstanceOf(CollectionInterface::class)
        ;
    }

    /*
     * Test find.
     */
    public function testFind()
    {
        $this
            ->given(
                $criteria = Criteria::gt(8),
                $items = array(8, 7, 1, 2, 3, 10, 9, 5, 4, 6),
                $collection = new ArrayCollection($items)
            )
            ->when($result = $collection->find($criteria))
            ->then
                ->collection($result)
                    ->isNotEmpty()
                    ->size
                        ->isEqualTo(2)
                ->collection($result)
                    ->hasAllElements
                        ->thatMatchToCriteria($criteria)
                    ->hasNoElements
                        ->thatMatchToCriteria(Criteria::lt(8))
        ;
    }

    /*
     * Test slice.
     */
    public function testSlice()
    {
        $this
            ->given(
                $criteria = Criteria::gt(3),
                $items = array(8, 7, 1, 2, 3, 10, 9, 5, 4, 6),
                $collection = new ArrayCollection($items)
            )
            ->when($result = $collection->find($criteria))
            ->and($resultSlice = $result->slice(2, 4))
            ->then
                ->collection($result)
                    ->isNotEmpty()
                        ->size
                            ->isEqualTo(7)
                ->collection($resultSlice)
                    ->isNotEmpty()
                        ->size
                            ->isEqualTo(4)
                ->collection($resultSlice)
                    ->hasAllElements
                        ->thatMatchToCriteria($criteria)
                    ->hasNoElements
                        ->thatMatchToCriteria(Criteria::lt(3))
        ;
    }
}
