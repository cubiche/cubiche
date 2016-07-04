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
use Cubiche\Core\Delegate\Tests\Fixtures\FooCallable;

/**
 * Delegate Tests class.
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

    /**
     * @param $value
     *
     * @return string
     */
    public static function sampleStaticMethod($value)
    {
        return $value.'-sufix';
    }

    /**
     * Test fromClosure method.
     */
    public function testFromClosure()
    {
        $this
            ->given($closure = function () {

            })
            ->when($delegate = Delegate::fromClosure($closure))
            ->then()
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
        ;
    }

    /**
     * Test fromMethod method.
     */
    public function testFromMethod()
    {
        $this
            ->when($delegate = Delegate::fromMethod($this, 'sampleMethod'))
            ->then()
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
        ;
    }

    /**
     * Test fromStaticMethod method.
     */
    public function testFromStaticMethod()
    {
        $this
            ->when($delegate = Delegate::fromStaticMethod(self::class, 'sampleStaticMethod'))
            ->then()
                ->object($delegate)
                    ->isInstanceOf(Delegate::class)
        ;

        $this
            ->when($delegate = new Delegate(self::class.'::sampleStaticMethod'))
            ->then()
                ->object($delegate)
                    ->isInstanceOf(Delegate::class)
        ;
    }

    /**
     * Test fromFunction method.
     */
    public function testFromFunction()
    {
        $this
            ->when($delegate = Delegate::fromFunction('array_filter'))
            ->then()
                ->object($delegate)
                ->isInstanceOf(Delegate::class)
                ->exception(
                    function () {
                        Delegate::fromFunction('foo');
                    }
                )->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test __invoke method.
     */
    public function testInvoke()
    {
        $this
            ->given($closure = function ($value = null) {
                return $value;
            })
            ->when($delegate = Delegate::fromClosure($closure))
            ->then()
                ->variable($delegate(5))
                    ->isEqualTo(5)
            ->given($delegate = Delegate::fromMethod($this, 'sampleMethod'))
            ->then()
                ->variable($delegate('text'))
                    ->isEqualTo('text-sufix')
            ->given($delegate = Delegate::fromStaticMethod(self::class, 'sampleStaticMethod'))
            ->then()
                ->variable($delegate('text'))
                    ->isEqualTo('text-sufix')
            ->given($delegate = Delegate::fromFunction('array_filter'))
            ->then()
                ->array($delegate(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], function ($value) {
                    return $value % 2 === 0;
                }))
                    ->isEqualTo(['b' => 2, 'd' => 4])
        ;
    }

    /**
     * Test reflection method.
     */
    public function testReflection()
    {
        $this
            ->given($closure = function ($value = null) {
                return $value;
            })
            ->when($reflection = Delegate::fromClosure($closure)->reflection())
            ->then()
                ->object($reflection)
                    ->isEqualTo(new \ReflectionFunction($closure))
        ;

        $this
            ->when($reflection = Delegate::fromMethod($this, 'sampleMethod')->reflection())
            ->then()
                ->object($reflection)
                    ->isEqualTo(new \ReflectionMethod($this, 'sampleMethod'))
        ;

        $this
            ->given($foo = new FooCallable())
            ->when($reflection = (new Delegate($foo))->reflection())
            ->then()
                ->object($reflection)
                    ->isEqualTo(new \ReflectionMethod($foo, '__invoke'));

        $this
            ->when($reflection = Delegate::fromStaticMethod(self::class, 'sampleStaticMethod')->reflection())
            ->then()
            ->object($reflection)
                ->isEqualTo(new \ReflectionMethod(self::class.'::sampleStaticMethod'))
        ;
    }
}
