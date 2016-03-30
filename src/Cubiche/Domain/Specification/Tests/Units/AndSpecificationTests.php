<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units;

use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\Criteria;

/**
 * AndSpecificationTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AndSpecificationTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        $left = Criteria::eq($value !== null ? $value : rand(1, 10));
        $right = Criteria::lt($value !== null ? $value : rand(1, 10));

        return new AndSpecification($left, $right);
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitAnd';
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
                    ->isInstanceOf(AndSpecification::class)
        ;
    }

    /**
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = new AndSpecification(Criteria::gt(25), Criteria::lte(30)))
            ->then()
                ->boolean($specification->evaluate(25))
                    ->isFalse()
                ->boolean($specification->evaluate(12))
                    ->isFalse()
                ->boolean($specification->evaluate(26))
                    ->isTrue()
                ->boolean($specification->evaluate(30))
                    ->isTrue()
        ;
    }
}
