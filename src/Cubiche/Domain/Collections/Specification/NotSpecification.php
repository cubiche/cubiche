<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification;

/**
 * Not Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotSpecification extends Specification
{
    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * @param SpecificationInterface $left
     * @param SpecificationInterface $right
     */
    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit()
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitNot($this);
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SpecificationInterface
     */
    public function specification()
    {
        return $this->specification;
    }
}
