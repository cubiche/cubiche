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
use Cubiche\Core\Specification\SpecificationVisitorInterface;

/**
 * Not Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotEqual extends BinaryConstraintOperator
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::evaluate()
     */
    public function evaluate($value)
    {
        $leftValue = $this->left()->apply($value);
        $rightValue = $this->right()->apply($value);

        if ($leftValue instanceof EquatableInterface) {
            return !$leftValue->equals($rightValue);
        }

        return $leftValue != $rightValue;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::acceptSpecificationVisitor()
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitNotEqual($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::not()
     */
    public function not()
    {
        return new Equal($this->left(), $this->right());
    }
}
