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
     * Test is method.
     */
    public function testIs()
    {
        $this
            ->given($enum = $this->newDefaultTestedInstance())
            ->then()
                ->boolean($enum->is(EnumFixture::FOO))
                    ->isTrue()
                ->boolean($enum->is(EnumFixture::BAR))
                    ->isFalse()
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
