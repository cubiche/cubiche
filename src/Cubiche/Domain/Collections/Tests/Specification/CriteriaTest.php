<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification;

use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\LessThan;
use Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Collections\Specification\Criteria;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Selector\Custom;
use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Model\Tests\TestCase;
use Cubiche\Domain\Collections\Specification\Constraint\Equal;
use Cubiche\Domain\Collections\Specification\Constraint\NotEqual;
use Cubiche\Domain\Collections\Specification\Constraint\Same;
use Cubiche\Domain\Collections\Specification\Constraint\NotSame;

/**
 * Criteria Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CriteriaTest extends TestCase
{
    /**
     * @test
     */
    public function testFalse()
    {
        $false = Criteria::false();
        $this->assertInstanceOf(Value::class, $false);
        $this->assertEquals(false, $false->value());
    }

    /**
     * @test
     */
    public function testTrue()
    {
        $true = Criteria::true();
        $this->assertInstanceOf(Value::class, $true);
        $this->assertEquals(true, $true->value());
    }

    /**
     * @test
     */
    public function testNull()
    {
        $null = Criteria::null();
        $this->assertInstanceOf(Value::class, $null);
        $this->assertEquals(null, $null->value());
    }

    /**
     * @test
     */
    public function testKey()
    {
        $key = Criteria::key('foo');
        $this->assertInstanceOf(Key::class, $key);
        $this->assertEquals('foo', $key->name());
    }

    /**
     * @test
     */
    public function testProperty()
    {
        $property = Criteria::property('foo');
        $this->assertInstanceOf(Property::class, $property);
        $this->assertEquals('foo', $property->name());
    }

    /**
     * @test
     */
    public function testMethod()
    {
        $method = Criteria::method('foo');
        $this->assertInstanceOf(Method::class, $method);
        $this->assertEquals('foo', $method->name());
        $this->assertEmpty($method->args());
    }

    /**
     * @test
     */
    public function testThis()
    {
        $self = Criteria::this();
        $this->assertInstanceOf(This::class, $self);
    }

    /**
     * @test
     */
    public function testCustom()
    {
        $custom = Criteria::custom(function ($value) {
            return $value + 1;
        });
        $this->assertInstanceOf(Custom::class, $custom);
        $this->assertEquals(3, $custom->apply(2));
    }

    /**
     * @test
     */
    public function testGt()
    {
        $gt = Criteria::gt(5);
        $this->assertInstanceOf(GreaterThan::class, $gt);

        $this->assertTrue($gt->evaluate(6));
        $this->assertFalse($gt->evaluate(5));
        $this->assertFalse($gt->evaluate(4));
    }

    /**
     * @test
     */
    public function testGte()
    {
        $gte = Criteria::gte(5);
        $this->assertInstanceOf(GreaterThanEqual::class, $gte);

        $this->assertTrue($gte->evaluate(6));
        $this->assertTrue($gte->evaluate(5));
        $this->assertFalse($gte->evaluate(4));
    }

    /**
     * @test
     */
    public function testLt()
    {
        $lt = Criteria::lt(5);
        $this->assertInstanceOf(LessThan::class, $lt);

        $this->assertFalse($lt->evaluate(6));
        $this->assertFalse($lt->evaluate(5));
        $this->assertTrue($lt->evaluate(4));
    }

    /**
     * @test
     */
    public function testLte()
    {
        $lte = Criteria::lte(5);
        $this->assertInstanceOf(LessThanEqual::class, $lte);

        $this->assertFalse($lte->evaluate(6));
        $this->assertTrue($lte->evaluate(5));
        $this->assertTrue($lte->evaluate(4));
    }

    /**
     * @test
     */
    public function testEq()
    {
        $eq = Criteria::eq(5);
        $this->assertInstanceOf(Equal::class, $eq);

        $this->assertTrue($eq->evaluate(5));
        $this->assertTrue($eq->evaluate(5.0));
        $this->assertFalse($eq->evaluate(4));
    }

    /**
     * @test
     */
    public function testNeq()
    {
        $neq = Criteria::neq(5);
        $this->assertInstanceOf(NotEqual::class, $neq);

        $this->assertFalse($neq->evaluate(5));
        $this->assertFalse($neq->evaluate(5.0));
        $this->assertTrue($neq->evaluate(4));
    }

    /**
     * @test
     */
    public function testSame()
    {
        $same = Criteria::same(5);
        $this->assertInstanceOf(Same::class, $same);

        $this->assertTrue($same->evaluate(5));
        $this->assertFalse($same->evaluate(5.0));
        $this->assertFalse($same->evaluate(4));
    }

    /**
     * @test
     */
    public function testNotSame()
    {
        $notsame = Criteria::notsame(5);
        $this->assertInstanceOf(NotSame::class, $notsame);

        $this->assertFalse($notsame->evaluate(5));
        $this->assertTrue($notsame->evaluate(5.0));
        $this->assertTrue($notsame->evaluate(4));
    }

    /**
     * @test
     */
    public function testIsNull()
    {
        $null = Criteria::isNull();
        $this->assertInstanceOf(Same::class, $null);

        $this->assertFalse($null->evaluate(5));
        $this->assertFalse($null->evaluate(0));
        $this->assertTrue($null->evaluate(null));
    }

    /**
     * @test
     */
    public function testNotNull()
    {
        $notnull = Criteria::notNull();
        $this->assertInstanceOf(NotSame::class, $notnull);

        $this->assertTrue($notnull->evaluate(5));
        $this->assertTrue($notnull->evaluate(0));
        $this->assertFalse($notnull->evaluate(null));
    }

    /**
     * @test
     */
    public function testAll()
    {
        $all = Criteria::all(Criteria::gt(5));

        $this->assertInstanceOf(All::class, $all);
        $this->assertTrue($all->evaluate(array(6, 7, 8, 9)));
        $this->assertTrue($all->evaluate(array()));
        $this->assertTrue($all->evaluate(6));
        $this->assertFalse($all->evaluate(array(6, 7, 8, 9, 5)));
    }
}
