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
 * Abstract Binary Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinarySpecification extends Specification
{
    /**
     * @var SpecificationInterface
     */
    protected $left;

    /**
     * @var SpecificationInterface
     */
    protected $right;

    /**
     * @param SpecificationInterface $left
     * @param SpecificationInterface $right
     */
    public function __construct(SpecificationInterface $left, SpecificationInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public function left()
    {
        return $this->left;
    }

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public function right()
    {
        return $this->right;
    }
}
