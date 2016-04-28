<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification;

/**
 * And Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AndSpecification extends BinarySpecification
{
    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->left()->evaluate($value) && $this->right()->evaluate($value);
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitAnd($this);
    }
}
