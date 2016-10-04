<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate\Quantifier;

use Cubiche\Core\Enumerable\Enumerable;

/**
 * At Least Quantifier Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AtLeast extends Quantifier
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @param int            $count
     * @param callable|mixed $selector
     * @param callable       $predicate
     */
    public function __construct($count, $selector, callable $predicate)
    {
        parent::__construct($selector, $predicate);

        $this->count = \max((int) $count, 0);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return Enumerable::from($this->selector()->apply($value))->atLeast($this->count(), $this->predicate());
    }
}
