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
use Cubiche\Core\Storage\Exception\KeyNotFoundException;

/**
 * KeyNotFoundExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class KeyNotFoundExceptionTests extends TestCase
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
     * Test forKey method.
     */
    public function testForKey()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = KeyNotFoundException::forKey('foo', $cause))
            ->then()
                ->object($exception)
                    ->isInstanceOf(KeyNotFoundException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = KeyNotFoundException::forKey('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
        ;
    }
}
