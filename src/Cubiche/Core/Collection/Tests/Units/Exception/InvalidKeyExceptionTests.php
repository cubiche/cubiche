<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Units\Exception;

use Cubiche\Core\Collection\Exception\InvalidKeyException;
use Cubiche\Tests\TestCase;

/**
 * InvalidKeyExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidKeyExceptionTests extends TestCase
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

    /*
     * Test forKey method.
     */
    public function testForKey()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = InvalidKeyException::forKey('foo', '', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(InvalidKeyException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = InvalidKeyException::forKey('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }
}