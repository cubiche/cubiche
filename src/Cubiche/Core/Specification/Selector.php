<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification;

use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Custom;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\SelectorVisitorInterface;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Specification\Constraint\Equal;
use Cubiche\Core\Specification\Constraint\GreaterThan;
use Cubiche\Core\Specification\Constraint\GreaterThanEqual;
use Cubiche\Core\Specification\Constraint\LessThan;
use Cubiche\Core\Specification\Constraint\LessThanEqual;
use Cubiche\Core\Specification\Constraint\NotEqual;
use Cubiche\Core\Specification\Constraint\NotSame;
use Cubiche\Core\Specification\Constraint\Same;
use Cubiche\Core\Specification\Quantifier\All;
use Cubiche\Core\Specification\Quantifier\AtLeast;

/**
 * Selector Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Selector extends Specification implements SelectorInterface
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @param SelectorInterface $selector
     */
    public function __construct(SelectorInterface $selector)
    {
        $this->selector = $selector;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->apply($value) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $this->selector()->apply($value);
    }

    /**
     * {@inheritdoc}
     */
    public function select(SelectorInterface $selector)
    {
        return new self($this->selector()->select($selector));
    }

    /**
     * @param SelectorVisitorInterface $visitor
     *
     * @return mixed
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $this->selector()->acceptSelectorVisitor($visitor);
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitSelector($this);
    }

    /**
     * @param string $key
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public function key($key)
    {
        return $this->select(new Key($key));
    }

    /**
     * @param string $property
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public function property($property)
    {
        return $this->select(new Property($property));
    }

    /**
     * @param string $method
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public function method($method)
    {
        return $this->select(new Method($method));
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Core\Specification\Selector
     */
    public function custom(callable $callable)
    {
        return $this->select(new Custom($callable));
    }

    /**
     * @return \Cubiche\Core\Specification\Selector
     */
    public function count()
    {
        return $this->select(new Count());
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\All
     */
    public function all(SpecificationInterface $specification)
    {
        return new All($this->selector(), $specification);
    }

    /**
     * @param int                    $count
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\AtLeast
     */
    public function atLeast($count, SpecificationInterface $specification)
    {
        return new AtLeast($count, $this->selector(), $specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Core\Specification\Quantifier\AtLeast
     */
    public function any(SpecificationInterface $specification)
    {
        return $this->atLeast(1, $specification);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\GreaterThan
     */
    public function gt($value)
    {
        return new GreaterThan($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\GreaterThanEqual
     */
    public function gte($value)
    {
        return new GreaterThanEqual($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\LessThan
     */
    public function lt($value)
    {
        return new LessThan($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\LessThanEqual
     */
    public function lte($value)
    {
        return new LessThanEqual($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\Equal
     */
    public function eq($value)
    {
        return new Equal($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\NotEqual
     */
    public function neq($value)
    {
        return new NotEqual($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public function same($value)
    {
        return new Same($this->selector(), $this->createSelector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\Constraint\NotSame
     */
    public function notSame($value)
    {
        return new NotSame($this->selector(), $this->createSelector($value));
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public function isNull()
    {
        return new Same($this->selector(), $this->createSelector(null));
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\NotSame
     */
    public function notNull()
    {
        return new NotSame($this->selector(), $this->createSelector(null));
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public function isTrue()
    {
        return new Same($this->selector(), $this->createSelector(true));
    }

    /**
     * @return \Cubiche\Core\Specification\Constraint\Same
     */
    public function isFalse()
    {
        return new Same($this->selector(), $this->createSelector(false));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Core\Specification\SelectorInterface
     */
    protected function createSelector($value)
    {
        return $value instanceof SelectorInterface ? $value : new Value($value);
    }
}
