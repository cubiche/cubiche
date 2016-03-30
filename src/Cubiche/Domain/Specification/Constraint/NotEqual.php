<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Constraint;

use Cubiche\Domain\Equatable\EquatableInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * Not Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotEqual extends BinarySelectorOperator
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::evaluate()
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
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitNotEqual($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Specification::not()
     */
    public function not()
    {
        return new Equal($this->left(), $this->right());
    }
}
