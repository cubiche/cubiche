<?php

/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Tests\Units\Exception;

use Cubiche\Core\CommandBus\Exception\InvalidCommandException;
use Cubiche\Core\CommandBus\Tests\Units\TestCase;

/**
 * InvalidCommandExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidCommandExceptionTests extends TestCase
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
            ->when($exception = InvalidCommandException::forUnknownValue('foo', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(InvalidCommandException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = InvalidCommandException::forUnknownValue('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }
}
