<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Storage\Tests\Units\Exception;

use Cubiche\Domain\Storage\Exception\KeyNotFoundException;
use Cubiche\Domain\Tests\Units\TestCase;

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
     * Test forKey method
     */
    public function testForKey()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = KeyNotFoundException::forKey('foo', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(KeyNotFoundException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = KeyNotFoundException::forKey('bar'))
            ->variable($exception->getPrevious())
            ->isNull()
        ;
    }
}
