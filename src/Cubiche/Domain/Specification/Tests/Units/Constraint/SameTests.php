<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Specification\Tests\Units\Constraint;

use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Tests\Units\SpecificationTestCase;

/**
 * SameTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SameTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return new Same(new This(), new Value($value !== null ? $value : rand(1, 10)));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitSame';
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
                    ->isInstanceOf(Same::class)
        ;
    }

    /*
     * Test not.
     */
    public function testNot()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then
            ->when($notSpecification = $specification->not($specification))
                ->object($notSpecification)
                    ->isInstanceOf(NotSame::class)
                ->object($notSpecification->left())
                    ->isIdenticalTo($specification->left())
                ->object($notSpecification->right())
                    ->isIdenticalTo($specification->right())
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification(5))
            ->then
                ->boolean($specification->evaluate(6))
                    ->isFalse()
                ->boolean($specification->evaluate(5.0))
                    ->isFalse()
                ->boolean($specification->evaluate(5))
                    ->isTrue()
        ;
    }
}
