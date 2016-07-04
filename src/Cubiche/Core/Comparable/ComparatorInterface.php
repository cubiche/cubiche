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

use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Comparator Interface.
 *
 * @method ComparatorInterface or(ComparatorInterface $other)
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorInterface extends VisiteeInterface
{
    /**
     * @param mixed $a
     * @param mixed $b
     *
     * @return int
     */
    public function compare($a, $b);

    /**
     * @return ComparatorInterface
     */
    public function reverse();

    /**
     * @param ComparatorInterface $other
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public function orX(ComparatorInterface $other);
}
