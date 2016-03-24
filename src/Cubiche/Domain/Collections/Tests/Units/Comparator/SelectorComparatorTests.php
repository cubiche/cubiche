<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\Comparator;

use Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface;
use Cubiche\Domain\Collections\Comparator\Order;
use Cubiche\Domain\Collections\Comparator\SelectorComparator;
use Cubiche\Domain\Collections\Tests\Fixtures\FooObject;
use Cubiche\Domain\Collections\Tests\Units\TestCase;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\SelectorInterface;

/**
 * SelectorComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SelectorComparatorTests extends TestCase
{
    /**
     * @param Order|null $oder
     *
     * @return SelectorComparator
     */
    protected function comparator(Order $oder = null)
    {
        return new SelectorComparator(Criteria::property('bar'), $oder === null ? Order::ASC() : $oder);
    }

    /**
     * @return string
     */
    protected function shouldVisitMethod()
    {
        return 'visitSelectorComparator';
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->object($comparator)
                    ->isInstanceOf(ComparatorInterface::class)
                ->object($comparator)
                    ->isInstanceOf(SelectorComparator::class)
                ->object($comparator->selector())
                    ->isInstanceOf(SelectorInterface::class)
                ->object($comparator->order())
                    ->isInstanceOf(Order::class)
        ;
    }

    /**
     * Test compare.
     */
    public function testCompare()
    {
        $this
            ->given($comparator = $this->comparator())
            ->then()
                ->integer($comparator->compare(new FooObject(1), new FooObject(2)))
                    ->isEqualTo(-1)
                ->integer($comparator->compare(new FooObject(1), new FooObject(1)))
                    ->isEqualTo(0)
                ->integer($comparator->compare(new FooObject(1), new FooObject(0)))
                    ->isEqualTo(1)
        ;

        $this
            ->given($comparator = $this->comparator(Order::DESC()))
            ->then()
                ->integer($comparator->compare(new FooObject(1), new FooObject(2)))
                    ->isEqualTo(1)
                ->integer($comparator->compare(new FooObject(1), new FooObject(1)))
                    ->isEqualTo(0)
                ->integer($comparator->compare(new FooObject(1), new FooObject(0)))
                    ->isEqualTo(-1)
        ;
    }

    /*
     * Test visit.
     */
    public function testVisit()
    {
        $shouldVisitMethod = 'visitSelectorComparator';

        $this
            ->let($mockClass = '\\mock\\'.ComparatorVisitorInterface::class)
            ->given($visitorMock = new $mockClass())
            ->calling($visitorMock)
            ->methods(
                function ($method) use ($shouldVisitMethod) {
                    return $method === strtolower($shouldVisitMethod);
                }
            )
            ->return = 25
        ;

        $this
            ->given($comparator = $this->comparator())
            ->when($result = $comparator->accept($visitorMock))
                ->mock($visitorMock)
                    ->call($shouldVisitMethod)
                        ->withArguments($comparator)
                        ->once()
                ->integer($result)
                    ->isEqualTo(25)
        ;
    }
}
