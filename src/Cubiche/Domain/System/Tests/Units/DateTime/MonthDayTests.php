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

use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Domain\System\DateTime\MonthDay;
use Cubiche\Domain\System\Tests\Units\TestCase;

/**
 * MonthDayTests class.
 *
 * Generated by TestGenerator on 2018-01-15 at 13:37:53.
 */
class MonthDayTests extends TestCase
{
    /**
     * Test FromNative method.
     */
    public function testFromNative()
    {
        $this
            ->given($fromNativeMonthDay = MonthDay::fromNative(21))
            ->and($monthDay = new MonthDay(21))
            ->then()
            ->object($monthDay)
            ->isEqualTo($fromNativeMonthDay)
            ->and()
            ->exception(function () {
                MonthDay::fromNative(-1);
            })->isInstanceOf(InvalidArgumentException::class)
            ->exception(function () {
                MonthDay::fromNative(32);
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
            ->and($fromNativeMonthDay = MonthDay::fromNativeDateTime($dateTime))
            ->and($monthDay = new MonthDay(13))
            ->and($monthDay15 = new MonthDay(15))
            ->then()
                ->object($monthDay)
                    ->isEqualTo($fromNativeMonthDay)
                ->object($monthDay15)
                    ->isNotEqualTo($fromNativeMonthDay)
        ;
    }
}
