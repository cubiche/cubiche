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
use Cubiche\Domain\Specification\Selector\This;

/**
 * ThisTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ThisTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::this();
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitThis';
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
                    ->isInstanceOf(This::class)
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
                ->boolean($specification->evaluate(true))
                    ->isTrue()
                ->boolean($specification->evaluate(false))
                    ->isFalse()
                ->boolean($specification->evaluate($this))
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
                ->boolean($specification->apply(true))
                    ->isTrue()
                ->boolean($specification->apply(false))
                    ->isFalse()
                ->object($specification->apply($this))
                    ->isEqualTo($this)
        ;
    }
}
