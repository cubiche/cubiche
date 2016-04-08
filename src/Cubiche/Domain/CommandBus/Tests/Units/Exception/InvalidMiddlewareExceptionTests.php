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

use Cubiche\Domain\CommandBus\Exception\InvalidMiddlewareException;
use Cubiche\Domain\CommandBus\Tests\Units\TestCase;

/**
 * InvalidMiddlewareExceptionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidMiddlewareExceptionTests extends TestCase
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
     * Test forMiddleware method.
     */
    public function testForMiddleware()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = InvalidMiddlewareException::forMiddleware('foo', $cause))
            ->then
                ->object($exception)
                    ->isInstanceOf(InvalidMiddlewareException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = InvalidMiddlewareException::forMiddleware('bar'))
            ->then
                ->variable($exception->getPrevious())->isNull()
        ;
    }
}
