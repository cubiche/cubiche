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

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * Abstract Relational Operator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RelationalOperator extends BinaryConstraintOperator
{
    /**
     * @var ComparatorInterface
     */
    private static $comparator = null;

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function comparison($value)
    {
        return self::comparator()->compare(
            $this->left()->apply($value),
            $this->right()->apply($value)
        );
    }

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    protected static function comparator()
    {
        if (self::$comparator === null) {
            self::$comparator = new Comparator();
        }

        return self::$comparator;
    }
}
