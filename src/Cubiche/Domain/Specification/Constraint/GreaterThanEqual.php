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

use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * Greater Than Equal Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class GreaterThanEqual extends RelationalOperator
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::evaluate()
     */
    public function evaluate($value)
    {
        return $this->comparison($value) >= 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitGreaterThanEqual($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Specification::not()
     */
    public function not()
    {
        return new LessThan($this->left(), $this->right());
    }
}
