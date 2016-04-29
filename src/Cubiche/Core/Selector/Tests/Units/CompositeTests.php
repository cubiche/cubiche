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

/**
 * Composite Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CompositeTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    public function newDefaultTestedInstance()
    {
        return $this->newTestedInstance(new Key('foo'), new Key('bar'));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitComposite';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Composite $composite */
            ->given($composite = $this->newDefaultTestedInstance())
            ->then()
                ->string($composite->apply(array('foo' => array('bar' => 'baz'))))
                    ->isEqualTo('baz')
                ->variable($composite->apply(null))
                    ->isNull()
                ->variable($composite->apply(array('foo')))
                    ->isNull()
        ;

        $this
            ->given($composite = $this->newTestedInstance(new Key('foo'), new Property('bar')))
            ->then()
                ->string($composite->apply(array('foo' => (object) array('bar' => 'baz'))))
                    ->isEqualTo('baz')
                ->exception(function () use ($composite) {
                    $composite->apply(array('foo' => null));
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($composite) {
                    $composite->apply(array('foo' => (object) array()));
                })->isInstanceOf(\RuntimeException::class)
        ;
    }

    /**
     * Test value/apply selector.
     */
    public function testValueApplySelector()
    {
        $this
            ->given($composite = $this->newDefaultTestedInstance())
            ->then()
                ->object($composite->valueSelector())
                    ->isInstanceOf(Key::class)
                ->string($composite->valueSelector()->name())
                    ->isEqualTo('foo')
                ->object($composite->applySelector())
                    ->isInstanceOf(Key::class)
                ->string($composite->applySelector()->name())
                    ->isEqualTo('bar')
        ;
    }
}
