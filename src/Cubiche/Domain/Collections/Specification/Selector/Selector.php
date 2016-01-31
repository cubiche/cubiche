<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Selector;

use Cubiche\Domain\Collections\Specification\Constraint\Equal;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\LessThan;
use Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotSame;
use Cubiche\Domain\Collections\Specification\Constraint\Same;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\Specification;
use Cubiche\Domain\Collections\Specification\SpecificationInterface;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends Specification implements SelectorInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\Quantifier\All
     */
    public function all(SpecificationInterface $specification)
    {
        return new All($this, $specification);
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThan
     */
    public function gt($value)
    {
        return new GreaterThan($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual
     */
    public function gte($value)
    {
        return new GreaterThanEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\LessThan
     */
    public function lt($value)
    {
        return new LessThan($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual
     */
    public function lte($value)
    {
        return new LessThanEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\Equal
     */
    public function eq($value)
    {
        return new Equal($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\NotEqual
     */
    public function neq($value)
    {
        return new NotEqual($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\Same
     */
    public function same($value)
    {
        return new Same($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\Constraint\NotSame
     */
    public function notsame($value)
    {
        return new NotSame($this, $this->selector($value));
    }

    /**
     * @param SelectorInterface|mixed $value
     *
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    protected function selector($value)
    {
        return $value instanceof SelectorInterface ? $value : new Value($value);
    }
}
