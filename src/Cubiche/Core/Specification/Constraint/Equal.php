<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification\Constraint;

use Cubiche\Core\Equatable\EquatableInterface;

/**
 * Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Equal extends BinaryConstraintOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        $leftValue = $this->left()->apply($value);
        $rightValue = $this->right()->apply($value);

        if ($leftValue instanceof EquatableInterface) {
            return $leftValue->equals($rightValue);
        }

        // If you compare a number with a string or the comparison involves numerical strings, then each string
        // is converted to a number. If some comparator is a string we will use the identical comparator
        // http://php.net/manual/en/language.operators.comparison.php
        if (is_string($leftValue) || is_string($rightValue)) {
            return $leftValue === $rightValue;
        }

        return $leftValue == $rightValue;
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new NotEqual($this->left(), $this->right());
    }
}
