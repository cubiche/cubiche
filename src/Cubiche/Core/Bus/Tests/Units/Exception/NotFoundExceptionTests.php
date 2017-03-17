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
     * Test handlerMethodNameForObject method.
     */
    public function testMethodNameForObject()
    {
        $this
            ->given($exception = NotFoundException::handlerMethodNameForObject('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(3)
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
     * Test nameOfCommand method.
     */
    public function testNameOfCommand()
    {
        $this
            ->given($exception = NotFoundException::nameOfCommand('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(2)
        ;
    }

    /**
     * Test nameOfQuery method.
     */
    public function testNameOfQuery()
    {
        $this
            ->given($exception = NotFoundException::nameOfQuery('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(4)
        ;
    }

    /**
     * Test methodForObject method.
     */
    public function testMethodForObject()
    {
        $this
            ->given($exception = NotFoundException::methodForObject('foo', 'bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
                ->integer($exception->getCode())
                    ->isEqualTo(7)
        ;
    }
}
