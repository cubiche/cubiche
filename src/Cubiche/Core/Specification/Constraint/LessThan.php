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
 * Less Than Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThan extends RelationalOperator
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::evaluate()
     */
    public function evaluate($value)
    {
        return $this->comparison($value) === -1;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::acceptSpecificationVisitor()
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitLessThan($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::not()
     */
    public function not()
    {
        return new GreaterThanEqual($this->left(), $this->right());
    }
}
