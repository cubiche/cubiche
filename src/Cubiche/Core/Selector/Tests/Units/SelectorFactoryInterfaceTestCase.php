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

use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorFactory;
use Cubiche\Core\Selector\SelectorFactoryInterface;
use Cubiche\Tests\TestCase;

/**
 * Selector Factory Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SelectorFactoryInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(SelectorFactoryInterface::class)
        ;
    }

    /**
     * Test addNamespace.
     */
    public function testAddNamespace()
    {
        $this
            /* @var \Cubiche\Core\Selector\SelectorFactoryInterface $factory */
            ->given($factory = $this->newDefaultTestedInstance())
            ->when($factory->addNamespace('Cubiche\Core\Selector'))
            ->then()
                /* @var \Cubiche\Core\Selector\Key $key */
                ->object($key = $factory->create('key', array('foo')))
                    ->isInstanceOf(Key::class)
                ->string($key->name())
                    ->isEqualto('foo')
        ;

        $this
            ->exception(function () use ($factory) {
                $factory->addNamespace(null);
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test addSelector.
     */
    public function testAddSelector()
    {
        $this
            /* @var \Cubiche\Core\Selector\SelectorFactoryInterface $factory */
            ->given($factory = $this->newDefaultTestedInstance())
            ->when($factory->addSelector(Key::class))
            ->then()
                /* @var \Cubiche\Core\Selector\Key $key */
                ->object($key = $factory->create('key', array('foo')))
                    ->isInstanceOf(Key::class)
                ->string($key->name())
                    ->isEqualto('foo')
        ;

        $this
            ->given($factory)
            ->when($factory->addSelector(Property::class, 'attribute'))
            ->then()
                /* @var \Cubiche\Core\Selector\Property $key */
                ->object($property = $factory->create('attribute', array('foo')))
                    ->isInstanceOf(Property::class)
                ->string($property->name())
                    ->isEqualto('foo')
        ;

        $this
            ->exception(function () use ($factory) {
                $factory->addSelector(Key::class);
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;

        $this
            ->exception(function () use ($factory) {
                $factory->addSelector('foo');
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;

        $this
            ->exception(function () use ($factory) {
                $factory->addSelector(SelectorFactory::class);
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            /* @var \Cubiche\Core\Selector\SelectorFactoryInterface $factory */
            ->given($factory = $this->newDefaultTestedInstance())
            ->exception(function () use ($factory) {
                $factory->addNamespace('foo');
                $factory->create('count');
            })
            ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
