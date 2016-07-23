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

/**
 * Any Quantifier Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Any extends AtLeast
{
    /**
     * @param callable|mixed $selector
     * @param callable       $predicate
     */
    public function __construct($selector, callable $predicate)
    {
        parent::__construct(1, $selector, $predicate);
    }
}
