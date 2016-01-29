<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\System\Real;
use Cubiche\Domain\System\Number;
use Cubiche\Domain\System\Integer;

/**
 * Real Test Case.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RealTestCase extends NumberTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\Tests\NativeValueObjectTestCase::firstNativeValue()
     */
    protected function firstNativeValue()
    {
        return 5.57;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\Tests\NativeValueObjectTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return 7.26;
    }

    /**
     * @return Cubiche\Domain\System\Real
     */
    protected function positiveInfinite()
    {
        return $this->fromNativeValue(INF);
    }

    /**
     * @return Cubiche\Domain\System\Real
     */
    protected function negativeInfinite()
    {
        return $this->fromNativeValue(-INF);
    }

    /**
     * @test
     */
    public function isInfinite()
    {
        parent::isInfinite();
        $this->assertTrue($this->positiveInfinite()->isInfinite());
        $this->assertTrue($this->negativeInfinite()->isInfinite());
    }

    /**
     * @test
     */
    public function isPositive()
    {
        parent::isPositive();
        $this->assertTrue($this->positiveInfinite()->isPositive());
        $this->assertFalse($this->negativeInfinite()->isPositive());
    }

    /**
     * @test
     */
    public function isNegative()
    {
        parent::isNegative();
        $this->assertFalse($this->positiveInfinite()->isNegative());
        $this->assertTrue($this->negativeInfinite()->isNegative());
    }

    /**
     * @test
     */
    public function addInfinite()
    {
        $this->assertTrue($this->number()->add($this->positiveInfinite())->equals($this->positiveInfinite()));
        $this->assertTrue($this->positiveInfinite()->add($this->number())->equals($this->positiveInfinite()));
        $this->assertTrue($this->number()->add($this->negativeInfinite())->equals($this->negativeInfinite()));
        $this->assertTrue($this->negativeInfinite()->add($this->number())->equals($this->negativeInfinite()));
        $this->assertTrue($this->positiveInfinite()->add($this->positiveInfinite())->equals($this->positiveInfinite()));
        $this->assertTrue($this->negativeInfinite()->add($this->negativeInfinite())->equals($this->negativeInfinite()));

        $this->setExpectedException(\Exception::class);
        $this->positiveInfinite()->add($this->negativeInfinite());
    }

    /**
     * @test
     */
    public function subInfinite()
    {
        $this->assertTrue($this->number()->sub($this->positiveInfinite())->equals($this->negativeInfinite()));
        $this->assertTrue($this->positiveInfinite()->sub($this->number())->equals($this->positiveInfinite()));
        $this->assertTrue($this->number()->sub($this->negativeInfinite())->equals($this->positiveInfinite()));
        $this->assertTrue($this->negativeInfinite()->sub($this->number())->equals($this->negativeInfinite()));
        $this->assertTrue($this->positiveInfinite()->sub($this->negativeInfinite())->equals($this->positiveInfinite()));
        $this->assertTrue($this->negativeInfinite()->sub($this->positiveInfinite())->equals($this->negativeInfinite()));

        $this->setExpectedException(\Exception::class);
        $this->positiveInfinite()->sub($this->positiveInfinite());
    }

    /**
     * @test
     */
    public function multInfinite()
    {
        $positiveInfinite = $this->positiveInfinite();
        $negativeInfinite = $this->negativeInfinite();
        $this->assertTrue($this->number()->mult($positiveInfinite)->equals($positiveInfinite));
        $this->assertTrue($positiveInfinite->mult($this->number())->equals($positiveInfinite));
        $this->assertTrue($this->number()->mult($negativeInfinite)->equals($negativeInfinite));
        $this->assertTrue($negativeInfinite->mult($this->number())->equals($negativeInfinite));

        $this->assertTrue($this->negativeValue()->mult($positiveInfinite)->equals($negativeInfinite));
        $this->assertTrue($positiveInfinite->mult($this->negativeValue())->equals($negativeInfinite));
        $this->assertTrue($this->negativeValue()->mult($negativeInfinite)->equals($positiveInfinite));
        $this->assertTrue($negativeInfinite->mult($this->negativeValue())->equals($positiveInfinite));

        $this->assertTrue($positiveInfinite->mult($positiveInfinite)->equals($positiveInfinite));
        $this->assertTrue($negativeInfinite->mult($negativeInfinite)->equals($positiveInfinite));
        $this->assertTrue($positiveInfinite->mult($negativeInfinite)->equals($negativeInfinite));
        $this->assertTrue($negativeInfinite->mult($positiveInfinite)->equals($negativeInfinite));

        $this->setExpectedException(\Exception::class);
        $positiveInfinite->mult($this->zero());
    }

    /**
     * @test
     */
    public function divInfinite()
    {
        $positiveInfinite = $this->positiveInfinite();
        $negativeInfinite = $this->negativeInfinite();
        $this->assertTrue($this->number()->div($positiveInfinite)->isZero());
        $this->assertTrue($this->number()->div($negativeInfinite)->isZero());

        $this->assertTrue($positiveInfinite->div($this->number())->equals($positiveInfinite));
        $this->assertTrue($negativeInfinite->div($this->number())->equals($negativeInfinite));
        $this->assertTrue($positiveInfinite->div($this->negativeValue())->equals($negativeInfinite));
        $this->assertTrue($negativeInfinite->div($this->negativeValue())->equals($positiveInfinite));

        $this->setExpectedException(\Exception::class);
        $positiveInfinite->div($positiveInfinite);
    }

    /**
     * @test
     */
    public function powInfinite()
    {
        $positiveInfinite = $this->positiveInfinite();
        $negativeInfinite = $this->negativeInfinite();

        $this->assertTrue($this->number()->pow($positiveInfinite)->equals($positiveInfinite));
        $this->assertTrue($this->number()->pow($negativeInfinite)->isZero());
        $this->assertTrue($positiveInfinite->pow($this->number())->equals($positiveInfinite));
        $this->assertTrue($positiveInfinite->pow($this->integerValue())->equals($positiveInfinite));
        $this->assertTrue($positiveInfinite->pow($this->realValue())->equals($positiveInfinite));
        $this->assertTrue($positiveInfinite->pow($this->negativeValue())->isZero());
        $this->assertTrue($negativeInfinite->pow($this->negativeValue())->isZero());
        $this->assertTrue(
            $negativeInfinite->pow($this->integerValue()->mult(Integer::fromNative(2)))->equals($positiveInfinite)
        );
        $this->assertTrue(
            $negativeInfinite->pow(
                $this->integerValue()->mult(Integer::fromNative(2))->inc()
            )->equals($negativeInfinite)
        );
    }

    /**
     * @test
     */
    public function compareTo()
    {
        parent::compareTo();

        $this->assertEquals(-1, $this->number()->compareTo($this->positiveInfinite()));
        $this->assertEquals(1, $this->number()->compareTo($this->negativeInfinite()));
        $this->assertEquals(-1, $this->negativeInfinite()->compareTo($this->number()));
        $this->assertEquals(1, $this->positiveInfinite()->compareTo($this->number()));

        $this->assertEquals(1, $this->positiveInfinite()->compareTo($this->negativeInfinite()));
        $this->assertEquals(-1, $this->negativeInfinite()->compareTo($this->positiveInfinite()));

        $this->setExpectedException(\Exception::class);
        $this->positiveInfinite()->compareTo($this->positiveInfinite());
    }
}
