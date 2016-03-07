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

use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\Tests\Units\SpecificationTestCase;

/**
 * LessThanTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LessThanTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification()
    {
        return new LessThan(new This(), new Value(rand(1, 10)));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitLessThan';
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
                    ->isInstanceOf(LessThan::class)
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
                    ->isInstanceOf(GreaterThanEqual::class)
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
            ->given($specification = new LessThan(new This(), new Value(5)))
            ->then
                ->boolean($specification->evaluate(6))
                    ->isFalse()
                ->boolean($specification->evaluate(5.0))
                    ->isFalse()
                ->boolean($specification->evaluate(4))
                    ->isTrue()
        ;
    }
}
