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
 * All Quantifier Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class All extends Quantifier
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return Enumerable::from($this->selector()->apply($value))->all($this->predicate());
    }
}
