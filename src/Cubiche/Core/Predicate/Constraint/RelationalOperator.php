<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate\Constraint;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Abstract Relational Operator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RelationalOperator extends BinaryConstraintOperator
{
    /**
     * @var ComparatorInterface
     */
    private $comparator = null;

    /**
     * @param callable|mixed $firstSelector
     * @param callable|mixed $secondSelector
     * @param callable       $comparator
     */
    public function __construct($firstSelector, $secondSelector, callable $comparator = null)
    {
        parent::__construct($firstSelector, $secondSelector);

        $this->comparator = Comparator::ensure($comparator);
    }

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function comparator()
    {
        return $this->comparator;
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function comparison($value)
    {
        return $this->comparator()->compare(
            $this->firstSelector->__invoke($value),
            $this->secondSelector->__invoke($value)
        );
    }
}
