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

use Cubiche\Core\Selector\Composite;
use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Custom;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorFactoryInterface;
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
     * {@inheritdoc}
     */
    protected function acceptVisitorDataProvider()
    {
        return array(
            array($this->newMockInstance(VisitorInterface::class), 'visit', 'accept'),
        );
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
     * Test addSelector.
     */
    public function testAddSelector()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selectors $builder */
            ->given($factory = $this->newMockInstance(SelectorFactoryInterface::class))
            ->when(function () use ($factory) {
                Selectors::setFactory($factory);
                Selectors::addSelector('foo', 'bar');
            })
            ->then()
                ->mock($factory)
                    ->call('addSelector')
                        ->withArguments('foo', 'bar')
                        ->once()
        ;
    }

    /**
     * Test addSelector.
     */
    public function testAddNamespace()
    {
        $this
            /* @var \Cubiche\Core\Selector\Selectors $builder */
            ->given($factory = $this->newMockInstance(SelectorFactoryInterface::class))
            ->when(function () use ($factory) {
                Selectors::setFactory($factory);
                Selectors::addNamespace('foo');
            })
            ->then()
                ->mock($factory)
                    ->call('addNamespace')
                        ->withArguments('foo')
                        ->once()
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
     * Test acceptSelectorVisitor.
     */
    public function testAcceptSelectorVisitor()
    {
        $this
            ->given(
                $visitorMock = $this->newMockVisitorInterface(),
                $shouldVisitMethod = 'visitComposite'
            )
            ->calling($visitorMock)
                ->methods(
                    function ($method) use ($shouldVisitMethod) {
                        return $method === \strtolower($shouldVisitMethod);
                    }
                )
                ->return = 25
            ;

        $this
                /* @var \Cubiche\Core\Selector\Selectors $builder */
                ->given($builder = $this->newDefaultTestedInstance())
                ->when($result = $builder->acceptSelectorVisitor($visitorMock))
                ->then()
                    ->mock($visitorMock)
                        ->call($shouldVisitMethod)
                        ->withArguments($builder->selector())
                    ->once()
                    ->integer($result)
                        ->isEqualTo(25)
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
            array(Selectors::custom(function () {

            }), Custom::class),
            array(Selectors::count(), Count::class),
            array(Selectors::value('foo'), Value::class),
            array(Selectors::composite(
                Selectors::value(array()),
                Selectors::count()
            ), Composite::class),
        );
    }
}
