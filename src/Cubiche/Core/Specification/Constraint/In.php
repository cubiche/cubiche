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
 * In class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class In extends RelationalOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        $values = $this->right()->apply($value);
        foreach ($values as $item) {
            $comparison = self::comparator()->compare($this->left()->apply($value), $item);
            if ($comparison === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new NotIn($this->left(), $this->right());
    }
}
