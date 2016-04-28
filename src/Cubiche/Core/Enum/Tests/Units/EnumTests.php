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

use Cubiche\Core\Enum\Tests\Units\Fixtures\EnumFixture;

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
}
