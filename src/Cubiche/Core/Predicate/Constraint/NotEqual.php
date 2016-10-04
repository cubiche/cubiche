<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate\Constraint;

/**
 * Not Equal Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotEqual extends EqualityOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !$this->comparison($value);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new Equal($this->firstSelector(), $this->secondSelector(), $this->comparer());
    }
}
