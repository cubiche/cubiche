<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Evaluator;

use Cubiche\Domain\Collections\Specification\AndSpecification;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\NotSpecification;
use Cubiche\Domain\Collections\Specification\OrSpecification;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Selector\Custom;
use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\Selector;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Specification\Specification;
use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;
use Cubiche\Domain\Comparable\ComparableInterface;

/**
 * Evaluator Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EvaluatorVisitor implements SpecificationVisitorInterface
{
    /**
     * @param Specification $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\Evaluator
     */
    public function evaluator(Specification $specification)
    {
        return $this->visit($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visit()
     */
    public function visit(Specification $specification)
    {
        return $specification->visit($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAnd()
     */
    public function visitAnd(AndSpecification $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            $leftEvaluator = $this->evaluator($specification->left());
            $rightEvaluator = $this->evaluator($specification->right());

            return $leftEvaluator->evaluate($value) && $rightEvaluator->evaluate($value);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitOr()
     */
    public function visitOr(OrSpecification $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            $leftEvaluator = $this->evaluator($specification->left());
            $rightEvaluator = $this->evaluator($specification->right());

            return $leftEvaluator->evaluate($value) || $rightEvaluator->evaluate($value);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNot()
     */
    public function visitNot(NotSpecification $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            $specificationEvaluator = $this->evaluator($specification->specification());

            return !$specificationEvaluator->evaluate($value);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitThis()
     */
    public function visitThis(This $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitCustom()
     */
    public function visitCustom(Custom $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAll()
     */
    public function visitAll(All $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            $items = $specification->selector()->apply($value);

            if (!is_array($items) && !$value instanceof \Traversable) {
                $items = array($items);
            }
            $specificationEvaluator = $this->evaluator($specification->specification());

            foreach ($items as $item) {
                if ($specificationEvaluator->evaluate($item) === false) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitGreaterThan()
     */
    public function visitGreaterThan(GreaterThan $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            $leftValue = $specification->left()->apply($value);
            $rightValue = $specification->right()->apply($value);

            if ($leftValue instanceof ComparableInterface) {
                return $leftValue->compareTo($rightValue) === 1;
            }

            return $leftValue > $rightValue;
        });
    }

    /**
     * @param Selector $specification
     *
     * @return Evaluator
     */
    protected function visitSelector(Selector $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $specification->apply($value) === true;
        });
    }
}
