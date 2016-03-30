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

use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\OrSpecification;

/**
 * OrSpecificationTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrSpecificationTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        $left = Criteria::eq($value !== null ? $value : rand(1, 10));
        $right = Criteria::lt($value !== null ? $value : rand(1, 10));

        return new OrSpecification($left, $right);
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitOr';
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
                    ->isInstanceOf(OrSpecification::class)
        ;
    }

    /**
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = new OrSpecification(Criteria::isFalse(), Criteria::same(5)))
            ->then()
                ->boolean($specification->evaluate(true))
                    ->isFalse()
                ->boolean($specification->evaluate(5.0))
                    ->isFalse()
                ->boolean($specification->evaluate(false))
                    ->isTrue()
                ->boolean($specification->evaluate(5))
                    ->isTrue()
        ;
    }
}
