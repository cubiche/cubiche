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
use Cubiche\Domain\Specification\Selector\Count;

/**
 * CountTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CountTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::count();
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitCount';
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
                    ->isInstanceOf(Count::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->boolean($specification->evaluate(array(4, 3, 6)))
                    ->isFalse()
                ->boolean($specification->evaluate(array()))
                    ->isFalse()
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
            ->given($specification = $this->randomSpecification())
            ->then()
                ->integer($specification->apply(array(6, 7, 8, 9)))
                    ->isEqualTo(4)
                ->integer($specification->apply(array(6, 7, 8, 9, 5)))
                    ->isEqualTo(5)
                ->integer($specification->apply(array()))
                    ->isEqualTo(0)
        ;
    }
}
