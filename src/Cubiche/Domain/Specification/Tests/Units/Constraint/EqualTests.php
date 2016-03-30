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

use Cubiche\Domain\Equatable\Tests\Fixtures\EquatableObject;
use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Criteria;

/**
 * EqualTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EqualTests extends BinarySelectorOperatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::eq($value !== null ? $value : rand(1, 10));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitEqual';
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->object($specification)
                    ->isInstanceOf(Equal::class)
        ;
    }

    /*
     * Test not.
     */
    public function testNot()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
            ->when($notSpecification = $specification->not())
                ->object($notSpecification)
                    ->isInstanceOf(NotEqual::class)
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
            ->then()
                ->boolean($specification->evaluate(5))
                    ->isTrue()
                ->boolean($specification->evaluate(5.0))
                    ->isTrue()
                ->boolean($specification->evaluate(4))
                    ->isFalse()
        ;

        $this
            ->given($specification = Criteria::eq(new EquatableObject(2)))
            ->then()
                ->boolean($specification->evaluate(new EquatableObject(2)))
                    ->isTrue()
                ->boolean($specification->evaluate(new EquatableObject(5.4)))
                    ->isFalse();
    }
}
