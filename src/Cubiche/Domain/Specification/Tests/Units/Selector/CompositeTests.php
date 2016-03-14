<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Selector;

use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;

/**
 * CompositeTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CompositeTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        if ($value !== null) {
            if (is_array($value) && count($value) == 2) {
                return new Composite($value[0], $value[1]);
            }

            throw new \Exception('The specification value should be and array with two composite specifications');
        }

        return new Composite(new Key(uniqid()), new Key(uniqid()));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitComposite';
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($specification = $this->randomSpecification())
            ->then
                ->object($specification)
                    ->isInstanceOf(Composite::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = Criteria::key('foo')->key('bar'))
            ->then()
                ->boolean($specification->evaluate(array('foo' => array('bar' => true))))
                    ->isTrue()
                ->boolean($specification->evaluate(array('foo' => array('bar' => false))))
                    ->isFalse()
                ->boolean($specification->evaluate(array('bar' => 'baz')))
                    ->isFalse()
        ;

        $this
            ->given($specification = Criteria::key('foo')->property('bar'))
            ->then()
                ->boolean($specification->evaluate(array('foo' => (object) array('bar' => true))))
                    ->isTrue()
                ->boolean($specification->evaluate(array('foo' => (object) array('bar' => false))))
                    ->isFalse()
                ->boolean($specification->evaluate(array('foo' => (object) array('bar' => 'baz'))))
                    ->isFalse()
                ->exception(function () use ($specification) {
                    $specification->evaluate(array('foo' => (object) array('property' => 'value')));
                })->isInstanceOf(\RuntimeException::class)
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = Criteria::key('foo')->key('bar'))
            ->then()
                ->string($specification->apply(array('foo' => array('bar' => 'some-value'))))
                    ->isEqualTo('some-value')
                ->variable($specification->apply(null))
                    ->isNull()
                ->variable($specification->apply(array('foo')))
                    ->isNull()
        ;

        $this
            ->given($specification = Criteria::key('foo')->property('bar'))
            ->then()
                ->string($specification->apply(array('foo' => (object) array('bar' => 'baz'))))
                    ->isEqualTo('baz')
                ->exception(function () use ($specification) {
                    $specification->apply(array('foo' => null));
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($specification) {
                    $specification->apply(array('foo' => (object) array()));
                })->isInstanceOf(\RuntimeException::class)
        ;
    }

    /**
     * @return bool
     */
    public function methodTrue()
    {
        return true;
    }

    /*
     * Test value/apply selector.
     */
    public function testValueApplySelector()
    {
        $this
            ->given($specification = Criteria::key('foo')->method('methodTrue'))
            ->then()
                ->boolean($specification->apply(array('foo' => $this)))
                    ->isTrue()
                ->object($specification->valueSelector())
                    ->isInstanceOf(Key::class)
                ->object($specification->applySelector())
                    ->isInstanceOf(Method::class)
        ;
    }
}
