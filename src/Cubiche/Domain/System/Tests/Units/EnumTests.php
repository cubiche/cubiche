<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\System\Tests\Units;

use Cubiche\Domain\Model\Tests\Units\NativeValueObjectTestCase;
use Cubiche\Domain\System\Tests\Units\Fixtures\EnumFixture;

/**
 * EnumTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EnumTests extends NativeValueObjectTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomNativeValue()
    {
        $values = array_slice(array_values(EnumFixture::toArray()), 1);

        return $values[array_rand($values)];
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeValue()
    {
        $values = array_values(EnumFixture::toArray());

        return $values[0];
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeValue()
    {
        return 3.14;
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return EnumFixture::fromNative($value);
    }

    /**
     * Test is method.
     */
    public function testIs()
    {
        $this
            ->given(
                $enum = $this->fromNative($this->randomNativeValue()),
                $enum1 = $this->fromNative($this->randomNativeValue())
            )
            ->then
                ->boolean($enum->is($enum->toNative()))
                    ->isTrue()
                ->boolean($enum1->is($this->invalidNativeValue()))
                    ->isFalse()
        ;
    }
}
