<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Selector;

use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\Specification;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends Specification implements SelectorInterface
{
    /**
     * @param unknown $key
     *
     * @return \Cubiche\Domain\Specification\Selector\Composite
     */
    public function key($key)
    {
        return new Composite($this, new Key($key));
    }

    /**
     * @param unknown $property
     *
     * @return \Cubiche\Domain\Specification\Selector\Composite
     */
    public function property($property)
    {
        return new Composite($this, new Property($property));
    }

    /**
     * @param unknown $method
     *
     * @return \Cubiche\Domain\Specification\Selector\Composite
     */
    public function method($method)
    {
        return new Composite($this, new Method($method));
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Domain\Specification\Selector\Composite
     */
    public function custom($callable)
    {
        return new Composite($this, new Custom($callable));
    }

    /**
     * @return \Cubiche\Domain\Specification\Selector\Composite
     */
    public function count()
    {
        return new Composite($this, new Count());
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\Quantifier\All
     */
    public function all(SpecificationInterface $specification)
    {
        return new All($this, $specification);
    }

    /**
     * @param int                    $count
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\Quantifier\AtLeast
     */
    public function atLeast($count, SpecificationInterface $specification)
    {
        return new AtLeast($count, $this, $specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Specification\Quantifier\AtLeast
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
        return new GreaterThan($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\GreaterThanEqual
     */
    public function gte($value)
    {
        return new GreaterThanEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\LessThan
     */
    public function lt($value)
    {
        return new LessThan($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\LessThanEqual
     */
    public function lte($value)
    {
        return new LessThanEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\Equal
     */
    public function eq($value)
    {
        return new Equal($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\NotEqual
     */
    public function neq($value)
    {
        return new NotEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\Same
     */
    public function same($value)
    {
        return new Same($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\Constraint\NotSame
     */
    public function notsame($value)
    {
        return new NotSame($this, $this->selector($value));
    }

    /**
     * @return \Cubiche\Domain\Specification\Constraint\Same
     */
    public function isNull()
    {
        return new Same($this, $this->selector(null));
    }

    /**
     * @return \Cubiche\Domain\Specification\Constraint\NotSame
     */
    public function notNull()
    {
        return new NotSame($this, $this->selector(null));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Specification\SelectorInterface
     */
    protected function selector($value)
    {
        return $value instanceof SelectorInterface ? $value : new Value($value);
    }
}
