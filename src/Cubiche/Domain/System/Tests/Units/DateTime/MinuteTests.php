<?php

/**
 * This file is part of the Cubiche/System component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\Tests\Units\DateTime;

use Cubiche\Domain\System\DateTime\Exception\InvalidArgumentException;
use Cubiche\Domain\System\DateTime\Minute;
use Cubiche\Domain\System\Tests\Units\TestCase;

/**
 * MinuteTests class.
 *
 * Generated by TestGenerator on 2018-01-15 at 13:37:53.
 */
class MinuteTests extends TestCase
{
    /**
     * Test FromNative method.
     */
    public function testFromNative()
    {
        $this
            ->given($fromNativeMinute = Minute::fromNative(21))
            ->and($minute = new Minute(21))
            ->then()
                ->object($minute)
                    ->isEqualTo($fromNativeMinute)
                ->and()
                ->exception(function () {
                    Minute::fromNative(-1);
                })->isInstanceOf(InvalidArgumentException::class)
                ->exception(function () {
                    Minute::fromNative(60);
                })->isInstanceOf(InvalidArgumentException::class)
        ;
    }

    /**
     * Test FromNativeDateTime method.
     */
    public function testFromNativeDateTime()
    {
        $this
            ->given($dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2015-04-13 15:05:20'))
            ->and($fromNativeMinute = Minute::fromNativeDateTime($dateTime))
            ->and($minute = new Minute(5))
            ->then()
                ->object($minute)
                    ->isEqualTo($fromNativeMinute)
        ;
    }
}
