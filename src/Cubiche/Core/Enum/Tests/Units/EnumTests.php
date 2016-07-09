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

use Cubiche\Core\Enum\Tests\Fixtures\EnumFixture;
use Cubiche\Core\Enum\Tests\Fixtures\DefaultEnumFixture;
use Cubiche\Core\Enum\Tests\Fixtures\BadDefaultEnumFixture;

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
        $this
            ->when($default = EnumFixture::__DEFAULT())
            ->then()
                ->object($default)
                    ->isEqualTo(EnumFixture::FOO())
        ;

        $this
            ->when($default = DefaultEnumFixture::__DEFAULT())
                ->then()
                    ->object($default)
                        ->isEqualTo(DefaultEnumFixture::BAR())
        ;

        $this
            ->exception(function () {
                BadDefaultEnumFixture::__DEFAULT();
            })
                ->isInstanceof(\UnexpectedValueException::class)
        ;

        $this
            ->exception(function () {
                BadDefaultEnumFixture::BAZ();
            })
                ->isInstanceof(\BadMethodCallException::class)
        ;
    }
}
