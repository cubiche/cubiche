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

use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\Specification;

/**
 * Abstract Binary Selector Operator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinarySelectorOperator extends Specification
{
    /**
     * @var SelectorInterface
     */
    protected $left;

    /**
     * @var SelectorInterface
     */
    protected $right;

    /**
     * @param SelectorInterface $left
     * @param SelectorInterface $right
     */
    public function __construct(SelectorInterface $left, SelectorInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    public function left()
    {
        return $this->left;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    public function right()
    {
        return $this->right;
    }
}
