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

use Cubiche\Core\Equatable\EqualityComparer;
use Cubiche\Core\Equatable\EqualityComparerInterface;

/**
 * Abstract Equality Operator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EqualityOperator extends BinaryConstraintOperator
{
    /**
     * @var EqualityComparerInterface
     */
    protected $equalityComparer;

    /**
     * @param callable|mixed $firstSelector
     * @param callable|mixed $secondSelector
     * @param callable       $equalityComparer
     */
    public function __construct($firstSelector, $secondSelector, callable $equalityComparer = null)
    {
        parent::__construct($firstSelector, $secondSelector);

        $this->equalityComparer = EqualityComparer::ensure($equalityComparer);
    }

    /**
     * @return \Cubiche\Core\Equatable\EqualityComparerInterface
     */
    public function comparer()
    {
        return $this->equalityComparer;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function comparison($value)
    {
        return $this->comparer()->equals(
            $this->firstSelector()->apply($value),
            $this->secondSelector()->apply($value)
        );
    }
}
