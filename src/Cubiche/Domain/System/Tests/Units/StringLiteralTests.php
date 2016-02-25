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
use Cubiche\Domain\System\StringLiteral;

/**
 * StringLiteralTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StringLiteralTests extends NativeValueObjectTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomNativeValue()
    {
        return uniqid();
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
    protected function uniqueNativeValue()
    {
        return uniqid();
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return StringLiteral::fromNative($value);
    }

    /**
     * Test isEmpty method.
     */
    public function testIsEmpty()
    {
        $this
            ->given(
                $literal = $this->fromNative($this->randomNativeValue()),
                $emptyLiteral = $this->fromNative('')
            )
            ->then
                ->boolean($literal->isEmpty())
                    ->isFalse()
                ->boolean($emptyLiteral->isEmpty())
                    ->isTrue()
        ;
    }
}
