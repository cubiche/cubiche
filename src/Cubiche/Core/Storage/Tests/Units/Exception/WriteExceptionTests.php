<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Storage\Tests\Units\Exception;

use Cubiche\Core\Storage\Tests\Units\TestCase;
use Cubiche\Core\Storage\Exception\WriteException;

/**
 * WriteExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class WriteExceptionTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(\RuntimeException::class)
        ;
    }

    /*
     * Test forException method.
     */
    public function testForKey()
    {
        $this
            ->given(
                $exceptionCode = 10,
                $cause = new \Exception('some cause', $exceptionCode)
            )
            ->when($exception = WriteException::forException($cause))
            ->then()
                ->object($exception)
                    ->isInstanceOf(WriteException::class)
                ->integer($exception->getCode())
                    ->isEqualTo($exceptionCode)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;
    }
}
