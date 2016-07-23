<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

use Cubiche\Core\Predicate\Predicate;
use Cubiche\Core\Predicate\Criteria;

/**
 * Quantifier Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Quantifier
{
    /**
     * @param callable $predicate
     *
     * @return bool
     */
    public function all(callable $predicate)
    {
        return !Enumerable::from($this)->any(Predicate::from($predicate)->not());
    }

    /**
     * @param callable $predicate
     *
     * @return bool
     */
    public function any(callable $predicate)
    {
        return $this->atLeast(1, $predicate);
    }

    /**
     * @param int      $count
     * @param callable $predicate
     *
     * @return bool
     */
    public function atLeast($count, callable $predicate)
    {
        return Enumerable::from($this)->where($predicate)->limit($count)->count() === $count;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($value, callable $equalityComparer = null)
    {
        return $this->any(Criteria::eq($value, $equalityComparer));
    }
}
