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
 * Greater Than Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class GreaterThanEqual extends RelationalOperator
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->comparison($value) >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitGreaterThanEqual($this);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new LessThan($this->left(), $this->right());
    }
}
