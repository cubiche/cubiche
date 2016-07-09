<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

use Cubiche\Core\Delegate\CallableInterface;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Comparator interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorInterface extends CallableInterface, VisiteeInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return int
     */
    public function compare($a, $b);

    /**
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function reverse();

    /**
     * @param callable $comparator
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function otherwise(callable $comparator);
}
