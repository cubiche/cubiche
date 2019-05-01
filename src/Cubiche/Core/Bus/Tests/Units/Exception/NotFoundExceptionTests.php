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

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * NotFoundExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotFoundExceptionTests extends TestCase
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

    /**
     * Test nameOfMessage method.
     */
    public function testNameOfMessage()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = NotFoundException::nameOfMessage('foo', $cause))
            ->then()
                ->object($exception)
                    ->isInstanceOf(NotFoundException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(1)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;
    }

    /**
     * Test handlerFor method.
     */
    public function testHandlerFor()
    {
        $this
            ->given($exception = NotFoundException::handlerFor('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(5)
        ;
    }

    /**
     * Test middlewareOfType method.
     */
    public function testMiddlewareOfType()
    {
        $this
            ->given($exception = NotFoundException::middlewareOfType('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(6)
        ;
    }

    /**
     * Test cannotHandleMessage method.
     */
    public function testCannotHandleMessage()
    {
        $this
            ->given($exception = NotFoundException::cannotHandleMessage('foo', 'bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(7)
        ;
    }
}
