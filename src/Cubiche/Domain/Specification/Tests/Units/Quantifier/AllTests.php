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
use Cubiche\Domain\Specification\Quantifier\All;

/**
 * AllTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AllTests extends QuantifierTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::all(Criteria::gt($value !== null ? $value : rand(1, 10)));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitAll';
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
                    ->isInstanceOf(All::class)
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
                ->boolean($specification->evaluate(array(6, 7, 8, 9)))
                    ->isTrue()
                ->boolean($specification->evaluate(array()))
                    ->isTrue()
                ->boolean($specification->evaluate(6))
                    ->isTrue()
                ->boolean($specification->evaluate(array(6, 7, 8, 9, 5)))
                    ->isFalse()
        ;
    }
}
