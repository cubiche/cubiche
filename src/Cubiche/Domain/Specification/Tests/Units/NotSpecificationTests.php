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
use Cubiche\Domain\Specification\NotSpecification;

/**
 * NotSpecificationTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotSpecificationTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return new NotSpecification(Criteria::lt($value !== null ? $value : rand(1, 10)));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitNot';
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
                    ->isInstanceOf(NotSpecification::class)
        ;
    }

    /**
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = new NotSpecification(Criteria::eq(25)))
            ->then()
                ->boolean($specification->evaluate(25))
                    ->isFalse()
                ->boolean($specification->evaluate(16))
                    ->isTrue()
        ;
    }
}
