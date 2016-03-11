<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Specification\Tests\Units\Selector;

use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Tests\Units\SpecificationTestCase;

/**
 * SelectorTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class SelectorTestCase extends SpecificationTestCase
{
    /*
     * Test key.
     */
    public function testKey()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($keySpecification = $specification->key('foo'))
                ->object($keySpecification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test property.
     */
    public function testProperty()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($propertySpecification = $specification->property('foo'))
                ->object($propertySpecification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test method.
     */
    public function testMethod()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($methodSpecification = $specification->method('foo'))
                ->object($methodSpecification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test custom.
     */
    public function testCustom()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($customSpecification = $specification->custom(function () {

            }))
                ->object($customSpecification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($countSpecification = $specification->count())
                ->object($countSpecification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test all.
     */
    public function testAll()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->and($property = $specification->property('foo'))
            ->then()
            ->when($allSpecification = $specification->all($property))
                ->object($allSpecification)
                    ->isInstanceOf(All::class)
        ;
    }

    /*
     * Test atLeast.
     */
    public function testAtLeast()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->and($property = $specification->property('foo'))
            ->then()
            ->when($atLeastSpecification = $specification->atLeast(3, $property))
                ->object($atLeastSpecification)
                    ->isInstanceOf(AtLeast::class)
        ;
    }

    /*
     * Test any.
     */
    public function testAny()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->and($property = $specification->property('foo'))
            ->then()
            ->when($anySpecification = $specification->any($property))
                ->object($anySpecification)
                    ->isInstanceOf(AtLeast::class)
        ;
    }

    /*
     * Test gt.
     */
    public function testGreaterThan()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($gtSpecification = $specification->gt(10))
                ->object($gtSpecification)
                    ->isInstanceOf(GreaterThan::class)
        ;
    }

    /*
     * Test gte.
     */
    public function testGreaterThanEqual()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($gteSpecification = $specification->gte(4.2))
                ->object($gteSpecification)
                    ->isInstanceOf(GreaterThanEqual::class)
        ;
    }

    /*
     * Test lt.
     */
    public function testLessThan()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($ltSpecification = $specification->lt(10))
                ->object($ltSpecification)
                    ->isInstanceOf(LessThan::class)
        ;
    }

    /*
     * Test lte.
     */
    public function testLessThanEqual()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($lteSpecification = $specification->lte(4.2))
                ->object($lteSpecification)
                    ->isInstanceOf(LessThanEqual::class)
        ;
    }

    /*
     * Test eq.
     */
    public function testEqual()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($eqSpecification = $specification->eq(4.2))
                ->object($eqSpecification)
                    ->isInstanceOf(Equal::class)
        ;
    }

    /*
     * Test neq.
     */
    public function testNotEqual()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($neqSpecification = $specification->neq(4.2))
                ->object($neqSpecification)
                    ->isInstanceOf(NotEqual::class)
        ;
    }

    /*
     * Test same.
     */
    public function testSame()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($sameSpecification = $specification->same(4.2))
                ->object($sameSpecification)
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test notSame.
     */
    public function testNotSame()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($notSameSpecification = $specification->notSame(4.2))
                ->object($notSameSpecification)
                    ->isInstanceOf(NotSame::class)
        ;
    }

    /*
     * Test isNull.
     */
    public function testIsNull()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($isNullSpecification = $specification->isNull())
                ->object($isNullSpecification)
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test notNull.
     */
    public function testNotNull()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($notNullSpecification = $specification->notNull())
                ->object($notNullSpecification)
                    ->isInstanceOf(NotSame::class)
        ;
    }

    /*
     * Test isTrue.
     */
    public function testIsTrue()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($isTrueSpecification = $specification->isTrue())
                ->object($isTrueSpecification)
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test isFalse.
     */
    public function testIsFalse()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($isFalseSpecification = $specification->isFalse())
                ->object($isFalseSpecification)
                    ->isInstanceOf(Same::class)
        ;
    }
}
