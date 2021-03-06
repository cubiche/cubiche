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
use Cubiche\Domain\System\DateTime\Year;
use Cubiche\Domain\System\Tests\Units\TestCase;

/**
 * YearTests class.
 *
 * Generated by TestGenerator on 2018-01-15 at 13:37:53.
 */
class YearTests extends TestCase
{
    /**
     * Test FromNative method.
     */
    public function testFromNative()
    {
        $this
            ->given($fromNativeYear = Year::fromNative(2018))
            ->and($year = new Year(2018))
            ->then()
                ->object($year)
                    ->isEqualTo($fromNativeYear)
                ->and()
                ->exception(function () {
                    Year::fromNative(-1);
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
            ->and($fromNativeYear = Year::fromNativeDateTime($dateTime))
            ->and($year = new Year(2015))
            ->then()
                ->object($year)
                    ->isEqualTo($fromNativeYear)
        ;
    }
}
