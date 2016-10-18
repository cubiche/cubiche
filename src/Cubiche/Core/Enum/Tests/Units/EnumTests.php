<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enum\Tests\Units;

use Cubiche\Core\Enum\Enum;
use Cubiche\Core\Enum\Tests\Fixtures\BadDefaultEnumFixture;
use Cubiche\Core\Enum\Tests\Fixtures\DefaultEnumFixture;
use Cubiche\Core\Enum\Tests\Fixtures\EnumFixture;

/**
 * Enum Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EnumTests extends EnumTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return new EnumFixture(EnumFixture::FOO);
    }

    /**
     * Test value method.
     */
    public function testValue()
    {
        $this
            ->given($enum = new EnumFixture(EnumFixture::FOO))
            ->when($value = $enum->value())
            ->then()
                ->variable($value)
                    ->isEqualTo(EnumFixture::FOO)
        ;
    }

    /**
     * Test name method.
     */
    public function testName()
    {
        $this
            ->given($enum = new EnumFixture(EnumFixture::FOO))
            ->when($name = $enum->name())
            ->then()
                ->variable($name)
                    ->isEqualTo('FOO')
        ;
    }

    /**
     * Test hashCode method.
     */
    public function testHashCode()
    {
        $this
            ->given($enum = new EnumFixture(EnumFixture::FOO))
            ->when($hashCode = $enum->hashCode())
            ->then()
                ->variable($hashCode)
                    ->isEqualTo((string) $enum->value())
        ;
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $this
            ->given($enum = new EnumFixture(EnumFixture::FOO))
            ->when($string = $enum->__toString())
            ->then()
                ->string($string)
                    ->isEqualTo((string) $enum->value())
        ;
    }

    /**
     * Test is method.
     */
    public function testIs()
    {
        $this
            ->given($enum = new EnumFixture(EnumFixture::FOO))
            ->then()
                ->boolean($enum->is(EnumFixture::FOO))
                    ->isTrue()
                ->boolean($enum->is(EnumFixture::BAR))
                    ->isFalse()
        ;
    }

    /**
     * Test isValidName method.
     */
    public function testIsValidName()
    {
        $this
            ->when($isValid = EnumFixture::isValidName('FOO'))
            ->then()
                ->boolean($isValid)
                    ->isTrue()
        ;

        $this
            ->when($isValid = EnumFixture::isValidName('ZOO'))
                ->then()
                ->boolean($isValid)
                ->isFalse()
        ;

        $this
            ->when($isValid = EnumFixture::isValidName('__DEFAULT'))
            ->then()
                ->boolean($isValid)
                    ->isFalse()
        ;
    }

    /**
     * Test names method.
     */
    public function testNames()
    {
        $this
            ->when($names = EnumFixture::names())
            ->then()
                ->array($names)
                    ->isEqualTo(array('FOO', 'BAR'))
        ;
    }

    /**
     * Test values method.
     */
    public function testValues()
    {
        $this
            ->when($values = EnumFixture::values())
            ->then()
                ->array($values)
                    ->isEqualTo(array('FOO' => EnumFixture::FOO(), 'BAR' => EnumFixture::BAR()))
        ;
    }

    /**
     * Test __DEFAULT method.
     */
    public function testDefault()
    {
        $this->equalityTest(EnumFixture::__DEFAULT(), EnumFixture::FOO());
        $this->equalityTest(DefaultEnumFixture::__DEFAULT(), DefaultEnumFixture::BAR());

        $this
            ->exception(function () {
                BadDefaultEnumFixture::__DEFAULT();
            })
                ->isInstanceof(\UnexpectedValueException::class)
            ->exception(function () {
                BadDefaultEnumFixture::BAZ();
            })
                ->isInstanceof(\BadMethodCallException::class)
        ;
    }

    /**
     * Test ensure method.
     */
    public function testEnsure()
    {
        $this->equalityTest(EnumFixture::ensure(EnumFixture::FOO()), EnumFixture::FOO());
        $this->equalityTest(EnumFixture::ensure(EnumFixture::FOO(), EnumFixture::BAR()), EnumFixture::FOO());
        $this->equalityTest(EnumFixture::ensure(), EnumFixture::__DEFAULT());
        $this->equalityTest(EnumFixture::ensure(null, EnumFixture::BAR()), EnumFixture::BAR());

        $this
            ->exception(function () {
                EnumFixture::ensure(DefaultEnumFixture::FOO());
            })
                ->isInstanceof(\InvalidArgumentException::class)
        ;
    }

    /**
     * @param Enum $enum1
     * @param Enum $enum2
     */
    protected function equalityTest(Enum $enum1, Enum $enum2)
    {
        $this
            ->given($enum1, $enum2)
            ->then()
                ->boolean($enum1->equals($enum2))
                    ->isTrue()
        ;
    }
}
