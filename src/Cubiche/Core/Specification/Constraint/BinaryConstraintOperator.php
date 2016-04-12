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

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Specification\Specification;

/**
 * Abstract Binary Constraint Operator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinaryConstraintOperator extends Specification
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
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function left()
    {
        return $this->left;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function right()
    {
        return $this->right;
    }
}
