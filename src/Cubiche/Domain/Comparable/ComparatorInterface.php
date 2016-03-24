<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable;

/**
 * Comparable Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ComparatorInterface
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
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function orX(ComparatorInterface $other);

    /**
     * @param ComparatorVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ComparatorVisitorInterface $visitor);
}
