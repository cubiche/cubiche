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

/**
 * Or Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class OrPredicate extends BinaryPredicate
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->firstOperand()->evaluate($value) || $this->secondOperand()->evaluate($value);
    }
}
