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
     * Test commandNameForObject method.
     */
    public function testCommandNameForObject()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = NotFoundException::commandNameForObject('foo', $cause))
            ->then()
                ->object($exception)
                    ->isInstanceOf(NotFoundException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;
    }

    /**
     * Test methodNameForObject method.
     */
    public function testMethodNameForObject()
    {
        $this
            ->given($exception = NotFoundException::methodNameForObject('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
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
        ;
    }

    /**
     * Test queryNameForObject method.
     */
    public function testQueryNameForObject()
    {
        $this
            ->given($exception = NotFoundException::queryNameForObject('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
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
        ;
    }
}
