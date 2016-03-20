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
use Cubiche\Domain\Specification\Selector\Method;

/**
 * MethodTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodTests extends FieldTestCase
{
    /**
     * @return bool
     */
    public function methodTrue()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function methodFalse()
    {
        return false;
    }

    /**
     * @param number $arg1
     * @param number $arg2
     *
     * @return number
     */
    public function methodWithArgs($arg1, $arg2)
    {
        return $arg1 + $arg2;
    }

    protected function privateMethod()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function randomSpecification($value = null)
    {
        return Criteria::method($value !== null ? $value : uniqid());
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitMethod';
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
                    ->isInstanceOf(Method::class)
        ;
    }

    /*
     * Test evaluate.
     */
    public function testEvaluate()
    {
        $this
            ->given($specification = $this->randomSpecification('methodTrue'))
            ->then()
                ->boolean($specification->evaluate($this))
                    ->isTrue()
        ;

        $this
            ->given($specification = $this->randomSpecification('methodFalse'))
            ->then()
                ->boolean($specification->evaluate($this))
                    ->isFalse()
        ;

        $this
            ->given($specification = $this->randomSpecification('methodWithArgs'))
            ->then()
                ->boolean($specification->with(1, 2)->evaluate($this))
                    ->isFalse()
        ;

        $this
            ->given($specification = $this->randomSpecification('methodWithArgs'))
            ->when($result = $specification->with(1, 2))
            ->then()
                ->object($result)
                    ->isInstanceOf(Method::class)
                    ->isEqualTo($specification)
                ->array($specification->args())
                    ->isEqualTo(array(1, 2))
        ;

        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->exception(function () use ($specification) {
                    $specification->evaluate(null);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($specification) {
                    $specification->evaluate($this);
                })->isInstanceOf(\RuntimeException::class)
        ;

        $this
            ->given($specification = $this->randomSpecification('privateMethod'))
            ->then()
                ->exception(function () use ($specification) {
                    $specification->evaluate($this);
                })->isInstanceOf(\RuntimeException::class)
        ;
    }

    /*
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($specification = $this->randomSpecification('name'))
            ->then()
                ->string($specification->apply($specification))
                    ->isEqualTo('name')
        ;

        $this
            ->given($specification = $this->randomSpecification('methodWithArgs'))
            ->then()
                ->integer($specification->with(1, 2)->apply($this))
                    ->isEqualTo(3)
        ;

        $this
            ->given($specification = $this->randomSpecification('foo'))
            ->then()
                ->exception(function () use ($specification) {
                    $specification->apply(null);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($specification) {
                    $specification->apply($this);
                })->isInstanceOf(\RuntimeException::class)
        ;

        $this
            ->given($specification = $this->randomSpecification('privateMethod'))
            ->then()
                ->exception(function () use ($specification) {
                    $specification->apply($this);
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
