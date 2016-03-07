<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Evaluator;

use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Equatable\EquatableInterface;
use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\Constraint\BinarySelectorOperator;
use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\NotSpecification;
use Cubiche\Domain\Specification\OrSpecification;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Specification\Quantifier\Quantifier;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Count;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\Selector;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\SelectorInterface;

/**
 * Evaluator Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EvaluatorBuilder implements SpecificationVisitorInterface
{
    /**
     * @var ComparatorInterface
     */
    protected $comparator;

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\EvaluatorInterface
     */
    public function evaluator(SpecificationInterface $specification)
    {
        return $specification->accept($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAnd()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitOr()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNot()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitThis()
     */
    public function visitThis(This $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitCustom()
     */
    public function visitCustom(Custom $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitCount()
     */
    public function visitCount(Count $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitComposite()
     */
    public function visitComposite(Composite $specification)
    {
        return $this->visitSelector($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitGreaterThan()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThan()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThanEqual()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitEqual()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotEqual()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitSame()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotSame()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAll()
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
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAtLeast()
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

        return $this->comparator()->compare($leftValue, $rightValue);
    }

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    protected function comparator()
    {
        if ($this->comparator === null) {
            $this->comparator = new Comparator();
        }

        return $this->comparator;
    }

    /**
     * @param SelectorInterface $specification
     *
     * @return Evaluator
     */
    protected function visitSelector(SelectorInterface $specification)
    {
        return Evaluator::fromClosure(function ($value) use ($specification) {
            return $specification->apply($value) === true;
        });
    }
}
