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

use Cubiche\Domain\Specification\Selector\Value;

/**
 * ValueTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValueTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return new Value($value !== null ? $value : rand(1, 10));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitValue';
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
                    ->isInstanceOf(Value::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification(5))
            ->then()
                ->boolean($specification->evaluate(null))
                    ->isFalse()
        ;

        $this
            ->given($specification = $this->randomSpecification(0))
            ->then()
                ->boolean($specification->evaluate(null))
                    ->isFalse()
        ;

        $this
            ->given($specification = $this->randomSpecification(true))
            ->then()
                ->boolean($specification->evaluate(null))
                    ->isTrue()
        ;

        $this
            ->given($specification = $this->randomSpecification(false))
            ->then()
                ->boolean($specification->evaluate(null))
                    ->isFalse()
        ;

        $this
            ->given($specification = $this->randomSpecification(null))
            ->then()
                ->boolean($specification->evaluate(null))
                    ->isFalse()
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = $this->randomSpecification(5))
            ->then()
                ->integer($specification->value())
                    ->isEqualTo(5)
                ->integer($specification->value())
                    ->isEqualTo($specification->apply(null))
        ;
    }
}
