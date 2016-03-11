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

use Cubiche\Domain\Specification\Selector\Custom;

/**
 * CustomTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CustomTests extends SelectorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return new Custom($value !== null ? $value : function () {

        });
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitCustom';
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
                    ->isInstanceOf(Custom::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification(function ($value) {
                return !$value;
            }))
            ->then()
                ->boolean($specification->evaluate(true))
                    ->isFalse()
                ->boolean($specification->evaluate(false))
                    ->isTrue()
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = $this->randomSpecification(function ($value) {
                return $value + 1;
            }))
            ->then()
                ->integer($specification->apply(1))
                    ->isEqualTo(2)
        ;
    }
}
