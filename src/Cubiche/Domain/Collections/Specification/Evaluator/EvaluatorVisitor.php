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
use Cubiche\Domain\Collections\Specification\Constraint\BinarySelectorOperator;
use Cubiche\Domain\Collections\Specification\Constraint\Equal;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\LessThan;
use Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotSame;
use Cubiche\Domain\Collections\Specification\Constraint\Same;
use Cubiche\Domain\Collections\Specification\NotSpecification;
use Cubiche\Domain\Collections\Specification\OrSpecification;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Collections\Specification\Quantifier\Quantifier;
use Cubiche\Domain\Collections\Specification\Selector\Composite;
use Cubiche\Domain\Collections\Specification\Selector\Count;
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
use Cubiche\Domain\Equatable\EquatableInterface;

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
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitCount()
     */
    public function visitCount(Count $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitComposite()
     */
    public function visitComposite(Composite $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitGreaterThan()
     */
    public function visitGreaterThan(GreaterThan $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->comparison($value, $specification) === 1;
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->comparison($value, $specification) >= 0;
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitLessThan()
     */
    public function visitLessThan(LessThan $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->comparison($value, $specification) === -1;
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitLessThanEqual()
     */
    public function visitLessThanEqual(LessThanEqual $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->comparison($value, $specification) <= 0;
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitEqual()
     */
    public function visitEqual(Equal $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->equals($value, $specification, true);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNotEqual()
     */
    public function visitNotEqual(NotEqual $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->equals($value, $specification, false);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitSame()
     */
    public function visitSame(Same $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->same($value, $specification, true);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNotSame()
     */
    public function visitNotSame(NotSame $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $this->same($value, $specification, false);
        });
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
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAtLeast()
     */
    public function visitAtLeast(AtLeast $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            if ($specification->count() == 0) {
                return true;
            }
            $count = 0;
            /** @var bool $result */
            foreach ($this->quantifierIterator($value, $specification) as $result) {
                if ($result) {
                    ++$count;
                    if ($specification->count() == $count) {
                        return true;
                    }
                }
            }

            return false;
        });
    }

    /**
     * @param unknown    $value
     * @param Quantifier $quantifier
     *
     * @return Generator
     */
    protected function quantifierIterator($value, Quantifier $quantifier)
    {
        $items = $quantifier->selector()->apply($value);

        if (!is_array($items) && !$value instanceof \Traversable) {
            $items = array($items);
        }

        $specificationEvaluator = $this->evaluator($quantifier->specification());

        foreach ($items as $item) {
            yield $specificationEvaluator->evaluate($item);
        }
    }

    /**
     * @param mixed                  $value
     * @param BinarySelectorOperator $operator
     * @param bool                   $same
     */
    protected function same($value, BinarySelectorOperator $operator, $same)
    {
        $leftValue = $operator->left()->apply($value);
        $rightValue = $operator->right()->apply($value);

        return $same ? $leftValue === $rightValue : $leftValue !== $rightValue;
    }

    /**
     * @param mixed                  $value
     * @param BinarySelectorOperator $operator
     * @param bool                   $equals
     */
    protected function equals($value, BinarySelectorOperator $operator, $equals)
    {
        $leftValue = $operator->left()->apply($value);
        $rightValue = $operator->right()->apply($value);

        if ($leftValue instanceof EquatableInterface) {
            return $equals ? $leftValue->equals($rightValue) : !$leftValue->equals($rightValue);
        }

        return $equals ? $leftValue == $rightValue : $leftValue != $rightValue;
    }

    /**
     * @param mixed                  $value
     * @param BinarySelectorOperator $operator
     *
     * @return int
     */
    protected function comparison($value, BinarySelectorOperator $operator)
    {
        $leftValue = $operator->left()->apply($value);
        $rightValue = $operator->right()->apply($value);

        if ($leftValue instanceof ComparableInterface) {
            return $leftValue->compareTo($rightValue);
        }

        return $leftValue < $rightValue ? -1 : ($leftValue == $rightValue ? 0 : 1);
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
