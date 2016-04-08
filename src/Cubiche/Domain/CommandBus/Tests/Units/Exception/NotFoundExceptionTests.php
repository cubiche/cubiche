<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Tests\Units\Exception;

use Cubiche\Domain\CommandBus\Exception\NotFoundException;
use Cubiche\Domain\CommandBus\Tests\Units\TestCase;

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

    /*
     * Test forCommand method.
     */
    public function testForCommand()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = NotFoundException::forCommand('foo', 'someType', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(NotFoundException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;
    }

    /*
     * Test classNameForCommand method.
     */
    public function testClassNameForCommand()
    {
        $this
            ->given($exception = NotFoundException::classNameForCommand('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }

    /*
     * Test methodNameForCommand method.
     */
    public function testMethodNameForCommand()
    {
        $this
            ->given($exception = NotFoundException::methodNameForCommand('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }

    /*
     * Test handlerForCommand method.
     */
    public function testHandlerForCommand()
    {
        $this
            ->given($exception = NotFoundException::handlerForCommand('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }
}
