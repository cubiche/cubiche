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

/**
 * NotIn class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotIn extends RelationalOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        foreach ($value as $item) {
            if ($this->comparison($item) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new In($this->left(), $this->right());
    }
}
