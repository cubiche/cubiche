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
 * Same Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Same extends BinaryConstraintOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->left()->apply($value) === $this->right()->apply($value);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new NotSame($this->left(), $this->right());
    }
}
