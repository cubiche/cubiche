<?php

/**
 * This file is part of the Cubiche/Web component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Web\Tests\Units;

use Cubiche\Domain\Model\Tests\Units\NativeValueObjectTestCase;
use Cubiche\Domain\Web\Port;

/**
 * PortTests class.
 *
 * Generated by TestGenerator on 2017-03-15 at 11:36:08.
 */
class PortTests extends NativeValueObjectTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomNativeValue()
    {
        return rand(1, 10);
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeValue()
    {
        return 20;
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeValue()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return Port::fromNative($value);
    }
}
