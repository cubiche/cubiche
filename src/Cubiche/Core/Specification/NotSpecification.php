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
     * @param SpecificationInterface $specification
     */
    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return !$this->specification()->evaluate($value);
    }

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public function specification()
    {
        return $this->specification;
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return $this->specification();
    }
}
