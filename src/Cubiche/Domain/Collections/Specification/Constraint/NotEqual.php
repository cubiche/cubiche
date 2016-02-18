<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Constraint;

use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;

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
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit()
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitNotEqual($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::not()
     */
    public function not()
    {
        return new Equal($this->left(), $this->right());
    }
}
