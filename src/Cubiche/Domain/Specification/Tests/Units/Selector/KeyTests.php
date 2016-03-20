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
use Cubiche\Domain\Specification\Selector\Key;

/**
 * KeyTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class KeyTests extends FieldTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::key($value !== null ? $value : uniqid());
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitKey';
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
                    ->isInstanceOf(Key::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->boolean($specification->evaluate(array('foo' => true)))
                    ->isTrue()
                ->boolean($specification->evaluate(array('foo' => false)))
                    ->isFalse()
                ->boolean($specification->evaluate(array('bar' => 'baz')))
                    ->isFalse()
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->string($specification->apply(array('foo' => 'bar')))
                    ->isEqualTo('bar')
                ->variable($specification->apply(null))
                    ->isNull()
                ->variable($specification->apply(array()))
                    ->isNull()
        ;
    }
}
