<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units;

use Cubiche\Core\Selector\Callback;
use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Specification\Constraint\BinaryConstraintOperator;
use Cubiche\Core\Specification\Constraint\Equal;
use Cubiche\Core\Specification\Constraint\GreaterThan;
use Cubiche\Core\Specification\Constraint\GreaterThanEqual;
use Cubiche\Core\Specification\Constraint\LessThan;
use Cubiche\Core\Specification\Constraint\LessThanEqual;
use Cubiche\Core\Specification\Constraint\NotEqual;
use Cubiche\Core\Specification\Constraint\NotSame;
use Cubiche\Core\Specification\Constraint\Same;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\Quantifier\All;
use Cubiche\Core\Specification\Quantifier\AtLeast;
use Cubiche\Core\Specification\Quantifier\Quantifier;
use Cubiche\Core\Specification\Selector;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Tests\TestCase;

/**
 * Criteria Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CriteriaTests extends TestCase
{
    /**
     * Test false.
     */
    public function testFalse()
    {
        $this->valueSelectorTest(Criteria::false(), false);
    }

    /**
     * Test true.
     */
    public function testTrue()
    {
        $this->valueSelectorTest(Criteria::true(), true);
    }

    /**
     * Test null.
     */
    public function testNull()
    {
        $this->valueSelectorTest(Criteria::null(), null);
    }

    /**
     * Test key.
     */
    public function testKey()
    {
        $this->fieldSelectorTest(Criteria::key('foo'), Key::class, 'foo');
    }

    /**
     * Test property.
     */
    public function testProperty()
    {
        $this->fieldSelectorTest(Criteria::property('foo'), Property::class, 'foo');
    }

    /**
     * Test method.
     */
    public function testMethod()
    {
        $this->fieldSelectorTest(Criteria::method('foo'), Method::class, 'foo');
    }

    /**
     * Test this.
     */
    public function testThis()
    {
        $this
            ->given($criteria = Criteria::this())
            ->then()
                ->object($criteria)
                    ->isInstanceOf(Selector::class)
                ->object($criteria->selector())
                    ->isInstanceOf(This::class)
        ;
    }

    /**
     * Test callback.
     */
    public function testCallback()
    {
        $this
            ->given($criteria = Criteria::callback(function () {

            }))
            ->then()
                ->object($criteria)
                    ->isInstanceOf(Selector::class)
                ->object($criteria->selector())
                    ->isInstanceOf(Callback::class)
        ;
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($criteria = Criteria::count())
            ->then()
                ->object($criteria)
                    ->isInstanceOf(Selector::class)
                ->object($criteria->selector())
                    ->isInstanceOf(Count::class)
        ;
    }

    /**
     * Test gt.
     */
    public function testGt()
    {
        $this->binaryConstraintTest(Criteria::gt(5), GreaterThan::class, 5);
    }

    /**
     * Test gte.
     */
    public function testGte()
    {
        $this->binaryConstraintTest(Criteria::gte(5), GreaterThanEqual::class, 5);
    }

    /**
     * Test lt.
     */
    public function testLt()
    {
        $this->binaryConstraintTest(Criteria::lt(5), LessThan::class, 5);
    }

    /**
     * Test lte.
     */
    public function testLte()
    {
        $this->binaryConstraintTest(Criteria::lte(5), LessThanEqual::class, 5);
    }

    /**
     * Test eq.
     */
    public function testEq()
    {
        $this->binaryConstraintTest(Criteria::eq(5), Equal::class, 5);
    }

    /**
     * Test neq.
     */
    public function testNeq()
    {
        $this->binaryConstraintTest(Criteria::neq(5), NotEqual::class, 5);
    }

    /**
     * Test same.
     */
    public function testSame()
    {
        $this->binaryConstraintTest(Criteria::same(5), Same::class, 5);
    }

    /**
     * Test notsame.
     */
    public function testNotsame()
    {
        $this->binaryConstraintTest(Criteria::notSame(5), NotSame::class, 5);
    }

    /**
     * Test isNull.
     */
    public function testIsNull()
    {
        $this->binaryConstraintTest(Criteria::isNull(), Same::class, null);
    }

    /**
     * Test notNull.
     */
    public function testNotNull()
    {
        $this->binaryConstraintTest(Criteria::notNull(), NotSame::class, null);
    }

    /**
     * Test isTrue.
     */
    public function testIsTrue()
    {
        $this->binaryConstraintTest(Criteria::isTrue(), Same::class, true);
    }

    /**
     * Test isFalse.
     */
    public function testIsFalse()
    {
        $this->binaryConstraintTest(Criteria::isFalse(), Same::class, false);
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $this->quantifierTest(Criteria::all($specification = Criteria::gt(5)), All::class, $specification);
    }

    /**
     * Test atLeast.
     */
    public function testAtLeast()
    {
        $this->atLeastTest(Criteria::atLeast(2, $specification = Criteria::gt(5)), 2, $specification);
    }

    /**
     * Test any.
     */
    public function testAny()
    {
        $this->atLeastTest(Criteria::any($specification = Criteria::gt(5)), 1, $specification);
    }

    /**
     * Test not.
     */
    public function testNot()
    {
        $this
            ->given($specification = Criteria::gt(5))
            ->given($criteria = Criteria::not($specification))
            ->then()
                ->variable($criteria)
                    ->isEqualTo($specification->not())
        ;
    }

    /**
     * @param Selector $criteria
     * @param mixed    $value
     */
    protected function valueSelectorTest($criteria, $value)
    {
        $this
            ->given($criteria, $value)
            ->then()
                ->object($criteria)
                    ->isInstanceOf(Selector::class)
                ->object($selector = $criteria->selector())
                    ->isInstanceOf(Value::class)
                ->variable($selector->value())
                    ->isEqualTo($value)
            ;
    }

    /**
     * @param Selector $criteria
     * @param string   $class
     * @param string   $name
     */
    protected function fieldSelectorTest($criteria, $class, $name)
    {
        $this
            ->given($criteria, $class, $name)
            ->then()
                ->object($criteria)
                    ->isInstanceOf(Selector::class)
                /* @var \Cubiche\Core\Selector\Field $selector */
                ->object($selector = $criteria->selector())
                    ->isInstanceOf($class)
                ->string($selector->name())
                    ->isEqualTo($name)
        ;
    }

    /**
     * @param BinaryConstraintOperator $constraint
     * @param string                   $class
     * @param mixed                    $value
     */
    protected function binaryConstraintTest($constraint, $class, $value)
    {
        $this
            ->given($constraint, $class, $value)
            ->then()
                ->object($constraint)
                    ->isInstanceOf($class)
                ->object($constraint->left())
                    ->isInstanceOf(This::class)
                /* @var \Cubiche\Core\Selector\Value $rightSelector */
                ->object($rightSelector = $constraint->right())
                    ->isInstanceOf(Value::class)
                ->variable($rightSelector->value())
                    ->isEqualTo($value)
        ;
    }

    /**
     * @param Quantifier             $quantifier
     * @param string                 $class
     * @param SpecificationInterface $specification
     */
    protected function quantifierTest($quantifier, $class, $specification)
    {
        $this
            ->given($quantifier, $class, $specification)
            ->then()
                ->object($quantifier)
                    ->isInstanceOf($class)
                ->object($quantifier->selector())
                    ->isInstanceOf(This::class)
                ->object($quantifier->specification())
                    ->isIdenticalTo($specification)
        ;
    }

    /**
     * @param AtLeast                $atLeast
     * @param int                    $count
     * @param SpecificationInterface $specification
     */
    protected function atLeastTest($atLeast, $count, $specification)
    {
        $this->quantifierTest($atLeast, AtLeast::class, $specification);

        $this
            ->given($atLeast, $count)
        ->then()
            ->integer($atLeast->count())
                ->isEqualTo($count)
        ;
    }
}
