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

use Cubiche\Core\Predicate\Quantifier\All;
use Cubiche\Core\Predicate\Quantifier\Any;
use Cubiche\Core\Predicate\Quantifier\AtLeast;

/**
 * Quantifier Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Quantifier
{
    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    abstract public function selector();

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\Quantifier\All
     */
    public function all(callable $predicate)
    {
        return new All($this->selector(), $predicate);
    }

    /**
     * @param int      $count
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\Quantifier\AtLeast
     */
    public function atLeast($count, callable $predicate)
    {
        return new AtLeast($count, $this->selector(), $predicate);
    }

    /**
     * @param callable $predicate
     *
     * @return \Cubiche\Core\Predicate\Quantifier\Any
     */
    public function any(callable $predicate)
    {
        return new Any($this->selector(), $predicate);
    }
}
