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
use Cubiche\Domain\Specification\Selector\Property;

/**
 * PropertyTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyTests extends FieldTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::property($value !== null ? $value : uniqid());
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitProperty';
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
                    ->isInstanceOf(Property::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->boolean($specification->evaluate((object) array('foo' => true)))
                    ->isTrue()
                ->boolean($specification->evaluate((object) array('foo' => false)))
                    ->isFalse()
                ->boolean($specification->evaluate((object) array('foo' => 'baz')))
                    ->isFalse()
                ->exception(function () use ($specification) {
                    $specification->evaluate((object) array('bar' => 'baz'));
                })->isInstanceOf(\RuntimeException::class)
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->string($specification->apply((object) array('foo' => 'bar')))
                    ->isEqualTo('bar')
                ->exception(function () use ($specification) {
                    $specification->apply(null);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($specification) {
                    $specification->apply((object) array());
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
