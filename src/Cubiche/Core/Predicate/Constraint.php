<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate;

use Cubiche\Core\Predicate\Constraint\Equal;
use Cubiche\Core\Predicate\Constraint\GreaterThan;
use Cubiche\Core\Predicate\Constraint\GreaterThanEqual;
use Cubiche\Core\Predicate\Constraint\LessThan;
use Cubiche\Core\Predicate\Constraint\LessThanEqual;
use Cubiche\Core\Predicate\Constraint\NotEqual;

/**
 * Constraint Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Constraint
{
    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    abstract public function selector();

    /**
     * @param callable|mixed $value
     * @param callable       $comparator
     *
     * @return \Cubiche\Core\Predicate\Constraint\GreaterThan
     */
    public function gt($value, callable $comparator = null)
    {
        return new GreaterThan($this->selector(), $value, $comparator);
    }

    /**
     * @param callable|mixed $value
     * @param callable       $comparator
     *
     * @return \Cubiche\Core\Predicate\Constraint\GreaterThanEqual
     */
    public function gte($value, callable $comparator = null)
    {
        return new GreaterThanEqual($this->selector(), $value);
    }

    /**
     * @param callable|mixed $value
     * @param callable       $comparator
     *
     * @return \Cubiche\Core\Predicate\Constraint\LessThan
     */
    public function lt($value, callable $comparator = null)
    {
        return new LessThan($this->selector(), $value);
    }

    /**
     * @param callable|mixed $value
     * @param callable       $comparator
     *
     * @return \Cubiche\Core\Predicate\Constraint\LessThanEqual
     */
    public function lte($value, callable $comparator = null)
    {
        return new LessThanEqual($this->selector(), $value);
    }

    /**
     * @param callable|mixed $value
     * @param callable       $equalityComparer
     *
     * @return \Cubiche\Core\Predicate\Constraint\Equal
     */
    public function eq($value, callable $equalityComparer = null)
    {
        return new Equal($this->selector(), $value, $equalityComparer);
    }

    /**
     * @param callable|mixed $value
     * @param callable       $equalityComparer
     *
     * @return \Cubiche\Core\Predicate\Constraint\NotEqual
     */
    public function neq($value, callable $equalityComparer = null)
    {
        return new NotEqual($this->selector(), $value, $equalityComparer);
    }

    /**
     * @return \Cubiche\Core\Predicate\Constraint\Equal
     */
    public function isNull()
    {
        return $this->eq(null);
    }

    /**
     * @return \Cubiche\Core\Predicate\Constraint\NotEqual
     */
    public function isNotNull()
    {
        return $this->neq(null);
    }

    /**
     * @return \Cubiche\Core\Predicate\Constraint\Equal
     */
    public function isTrue()
    {
        return $this->eq(true);
    }

    /**
     * @return \Cubiche\Core\Predicate\Constraint\Equal
     */
    public function isFalse()
    {
        return $this->eq(false);
    }
}
