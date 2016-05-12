<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Exception;

use Cubiche\Core\Bus\Exception\InvalidMiddlewareException;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * InvalidMiddlewareExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidMiddlewareExceptionTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test forUnknownValue method.
     */
    public function testForUnknownValue()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = InvalidMiddlewareException::forUnknownValue('foo', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(InvalidMiddlewareException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = InvalidMiddlewareException::forUnknownValue('bar'))
            ->then
                ->variable($exception->getPrevious())
                    ->isNull()
        ;
    }
}
