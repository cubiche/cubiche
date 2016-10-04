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

use Cubiche\Core\Selector\Callback;
use Cubiche\Core\Selector\Composite;
use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\Selectors;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Selector Builder Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SelectorsTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return Selectors::key('foo')->key('bar');
    }

    /**
     * Test apply.
     */
    public function testSelector()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selectors $builder */
            ->given($builder = $this->newDefaultTestedInstance())
            ->then()
                /* @var \Cubiche\Core\Selector\Composite $selector */
                ->object($selector = $builder->selector())
                    ->isInstanceOf(Composite::class)
                /* @var \Cubiche\Core\Selector\Key $foo */
                ->object($foo = $selector->valueSelector())
                    ->isInstanceOf(Key::class)
                ->string($foo->name())
                    ->isEqualTo('foo')
                    /* @var \Cubiche\Core\Selector\Key $foo */
                ->object($bar = $selector->applySelector())
                    ->isInstanceOf(Key::class)
                ->string($bar->name())
                    ->isEqualTo('bar')
        ;
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selectors $builder */
            ->given($builder = $this->newDefaultTestedInstance())
            ->then()
                ->string($builder->apply(array('foo' => array('bar' => 'baz'))))
                    ->isEqualTo('baz')
        ;
    }

    /**
     * Test __staticCall.
     *
     * @dataProvider staticCallDataProvider
     */
    public function testStaticCall(Selectors $builder, $expectedClass)
    {
        $this
            ->given($builder, $expectedClass)
            ->then()
                ->object($builder->selector())
                    ->isInstanceOf($expectedClass)
        ;
    }

    /**
     * @return string[][]
     */
    protected function staticCallDataProvider()
    {
        return array(
            array(Selectors::key('foo'), Key::class),
            array(Selectors::property('foo'), Property::class),
            array(Selectors::method('foo'), Method::class),
            array(Selectors::callback(function () {

            }), Callback::class),
            array(Selectors::count(), Count::class),
            array(Selectors::value('foo'), Value::class),
            array(Selectors::composite(
                Selectors::value(array()),
                Selectors::count()
            ), Composite::class),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function acceptVisitorDataProvider()
    {
        /** @var \Cubiche\Core\Selector\Selectors $visitee */
        $visitee = $this->newDefaultTestedInstance();

        return array(
            array($visitee, $this->newMockInstance(VisitorInterface::class), 'visit', $visitee->selector()),
        );
    }
}
