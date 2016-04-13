<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Delegate\Tests\Units;

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Tests\TestCase;

/**
 * DelegateTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DelegateTests extends TestCase
{
    /**
     * @param $value
     *
     * @return string
     */
    public function sampleMethod($value)
    {
        return $value.'-sufix';
    }

    /*
     * Test fromClosure method.
     */
    public function testFromClosure()
    {
        $this
            ->given($closure = function () {

            })
            ->when($delegate = Delegate::fromClosure($closure))
            ->then
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
        ;
    }

    /*
     * Test fromMethod method.
     */
    public function testFromMethod()
    {
        $this
            ->when($delegate = Delegate::fromMethod($this, 'sampleMethod'))
            ->then
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
        ;
    }

    /*
     * Test fromFunction method.
     */
    public function testFromFunction()
    {
        $this
            ->when($delegate = Delegate::fromFunction('array_filter'))
            ->then
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
                ->exception(
                    function () {
                        Delegate::fromFunction('foo');
                    }
                )->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /*
     * Test __invoke method.
     */
    public function testInvoke()
    {
        $this
            ->given($closure = function ($value = null) {
                return $value;
            })
            ->when($delegate = Delegate::fromClosure($closure))
            ->then
                ->variable($delegate(5))
                    ->isEqualTo(5)
            ->given($delegate = Delegate::fromMethod($this, 'sampleMethod'))
            ->then
                ->variable($delegate('text'))
                    ->isEqualTo('text-sufix')
            ->given($delegate = Delegate::fromFunction('array_filter'))
            ->then
                ->array($delegate(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], function ($value) {
                    return $value % 2 === 0;
                }))
                    ->isEqualTo(['b' => 2, 'd' => 4])
        ;
    }
}
