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

use Cubiche\Core\Specification\SpecificationVisitorInterface;

/**
 * Less Than Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThanEqual extends RelationalOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->comparison($value) <= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitLessThanEqual($this);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new GreaterThan($this->left(), $this->right());
    }
}
