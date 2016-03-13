<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units;

use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Specification\Selector\Count;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * CriteriaTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CriteriaTests extends TestCase
{
    /*
     * Test false.
     */
    public function testFalse()
    {
        $this
            ->given($criteria = Criteria::false())
            ->then
                ->object($criteria)
                    ->isInstanceOf(Value::class)
                ->boolean($criteria->value())
                    ->isFalse()
        ;
    }

    /*
     * Test true.
     */
    public function testTrue()
    {
        $this
            ->given($criteria = Criteria::true())
            ->then
                ->object($criteria)
                    ->isInstanceOf(Value::class)
                ->boolean($criteria->value())
                    ->isTrue()
        ;
    }

    /*
     * Test null.
     */
    public function testNull()
    {
        $this
            ->given($criteria = Criteria::null())
            ->then
                ->object($criteria)
                    ->isInstanceOf(Value::class)
                ->variable($criteria->value())
                    ->isNull()
        ;
    }

    /*
     * Test key.
     */
    public function testKey()
    {
        $this
            ->given($criteria = Criteria::key('foo'))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Key::class)
                ->string($criteria->name())
                    ->isEqualTo('foo')
        ;
    }

    /*
     * Test property.
     */
    public function testProperty()
    {
        $this
            ->given($criteria = Criteria::property('foo'))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Property::class)
                ->string($criteria->name())
                    ->isEqualTo('foo')
        ;
    }

    /*
     * Test method.
     */
    public function testMethod()
    {
        $this
            ->given($criteria = Criteria::method('foo'))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Method::class)
                ->string($criteria->name())
                    ->isEqualTo('foo')
                ->array($criteria->args())
                    ->isEmpty()
        ;
    }

    /*
     * Test this.
     */
    public function testThis()
    {
        $this
            ->given($criteria = Criteria::this())
            ->then
                ->object($criteria)
                    ->isInstanceOf(This::class)
        ;
    }

    /*
     * Test custom.
     */
    public function testCustom()
    {
        $this
            ->given($criteria = Criteria::custom(function () {

            }))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Custom::class)
        ;
    }

    /*
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($criteria = Criteria::count())
            ->then
                ->object($criteria)
                    ->isInstanceOf(Count::class)
        ;
    }

    /*
     * Test gt.
     */
    public function testGt()
    {
        $this
            ->given($criteria = Criteria::gt(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(GreaterThan::class)
        ;
    }

    /*
     * Test gte.
     */
    public function testGte()
    {
        $this
            ->given($criteria = Criteria::gte(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(GreaterThanEqual::class)
        ;
    }

    /*
     * Test lt.
     */
    public function testLt()
    {
        $this
            ->given($criteria = Criteria::lt(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(LessThan::class)
        ;
    }

    /*
     * Test lte.
     */
    public function testLte()
    {
        $this
            ->given($criteria = Criteria::lte(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(LessThanEqual::class)
        ;
    }

    /*
     * Test eq.
     */
    public function testEq()
    {
        $this
            ->given($criteria = Criteria::eq(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Equal::class)
        ;
    }

    /*
     * Test neq.
     */
    public function testNeq()
    {
        $this
            ->given($criteria = Criteria::neq(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(NotEqual::class)
        ;
    }

    /*
     * Test same.
     */
    public function testSame()
    {
        $this
            ->given($criteria = Criteria::same(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test notsame.
     */
    public function testNotsame()
    {
        $this
            ->given($criteria = Criteria::notsame(5))
            ->then
                ->object($criteria)
                    ->isInstanceOf(NotSame::class)
        ;
    }

    /*
     * Test isNull.
     */
    public function testIsNull()
    {
        $this
            ->given($criteria = Criteria::isNull())
            ->then
                ->object($criteria)
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test notNull.
     */
    public function testNotNull()
    {
        $this
            ->given($criteria = Criteria::notNull())
            ->then
                ->object($criteria)
                    ->isInstanceOf(NotSame::class)
        ;
    }

    /*
     * Test all.
     */
    public function testAll()
    {
        $this
            ->given($criteria = Criteria::all(Criteria::gt(5)))
            ->then
                ->object($criteria)
                    ->isInstanceOf(All::class)
        ;
    }

    /*
     * Test atLeast.
     */
    public function testAtLeast()
    {
        $this
            ->given($criteria = Criteria::atLeast(2, Criteria::gt(5)))
            ->then
                ->object($criteria)
                    ->isInstanceOf(AtLeast::class)
        ;
    }

    /*
     * Test any.
     */
    public function testAny()
    {
        $this
            ->given($criteria = Criteria::any(Criteria::gt(5)))
            ->then
                ->object($criteria)
                    ->isInstanceOf(AtLeast::class)
        ;
    }

    /*
     * Test not.
     */
    public function testNot()
    {
        $this
            ->given($criteria = Criteria::not(Criteria::gt(5)))
            ->then
                ->object($criteria)
                    ->isInstanceOf(LessThanEqual::class)
        ;
    }
}
