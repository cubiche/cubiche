<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Quantifier;

use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Quantifier\AtLeast;

/**
 * AtLeastTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AtLeastTests extends QuantifierTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::atLeast(2, Criteria::gt($value !== null ? $value : rand(1, 10)));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitAtLeast';
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
                    ->isInstanceOf(AtLeast::class)
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
                ->boolean($specification->evaluate(array(4, 6, 5, 3, 9)))
                    ->isTrue()
                ->boolean($specification->evaluate(array()))
                    ->isFalse()
                ->boolean($specification->evaluate(6))
                    ->isFalse()
                ->boolean($specification->evaluate(array(1, 2, 3, 4, 5, 6)))
                    ->isFalse()
        ;
    }
}
