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

use Cubiche\Domain\CommandBus\Exception\InvalidLocatorException;
use Cubiche\Domain\CommandBus\Tests\Units\TestCase;

/**
 * InvalidLocatorExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidLocatorExceptionTests extends TestCase
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
     * Test forUnknownValue method.
     */
    public function testForUnknownValue()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = InvalidLocatorException::forUnknownValue('foo', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(InvalidLocatorException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = InvalidLocatorException::forUnknownValue('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }
}
