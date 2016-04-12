<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Custom;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorInterface;

/**
 * Selector Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SelectorTestCase extends SelectorInterfaceTestCase
{
    /**
     * Test select.
     */
    public function testSelect()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selector $selector */
            ->given($selector = $this->newDefaultTestedInstance())
            ->then()
            ->when($selected = $selector->select($this->newDefaultTestedInstance()))
                ->object($selected)
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }

    /**
     * Test key.
     */
    public function testKey()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selector $selector */
            ->given($selector = $this->newDefaultTestedInstance())
            ->let($key = $selector->key('foo'))
            ->let($selected = $selector->select(new Key('foo')))
            ->then()
                ->object($key)
                    ->isInstanceOf(SelectorInterface::class)
                    ->isEqualTo($selected)
        ;
    }

    /**
     * Test property.
     */
    public function testProperty()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selector $selector */
            ->given($selector = $this->newDefaultTestedInstance())
            ->let($property = $selector->property('foo'))
            ->let($selected = $selector->select(new Property('foo')))
            ->then()
                ->object($property)
                    ->isInstanceOf(SelectorInterface::class)
                    ->isEqualTo($selected)
        ;
    }

    /**
     * Test method.
     */
    public function testMethod()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selector $selector */
            ->given($selector = $this->newDefaultTestedInstance())
            ->let($method = $selector->method('foo'))
            ->let($selected = $selector->select(new Method('foo')))
            ->then()
                ->object($method)
                    ->isInstanceOf(SelectorInterface::class)
                    ->isEqualTo($selected)
        ;
    }

    /**
     * Test custom.
     */
    public function testCustom()
    {
        $this
            ->given($selector = $this->newDefaultTestedInstance())
            ->define($callable = function () {
            })
            ->let($custom = $selector->custom($callable))
            ->let($selected = $selector->select(new Custom($callable)))
            ->then()
                ->object($custom)
                    ->isInstanceOf(SelectorInterface::class)
                    ->isEqualTo($selected)
        ;
    }

    /**
     * Test count.
     */
    public function testCount()
    {
        $this
            ->given($selector = $this->newDefaultTestedInstance())
            ->let($count = $selector->count())
            ->let($selected = $selector->select(new Count()))
            ->then()
                ->object($count)
                    ->isInstanceOf(SelectorInterface::class)
                    ->isEqualTo($selected)
        ;
    }
}
