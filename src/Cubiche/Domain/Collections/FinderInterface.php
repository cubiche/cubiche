<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Finder Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface FinderInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return int
     */
    public function length();

    /**
     * @return int
     */
    public function offset();

    /**
     * @return \Cubiche\Domain\Specification\SpecificationInterface
     */
    public function specification();

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function comparator();

    /**
     * @param int $offset
     * @param int $length
     *
     * @return FinderInterface
     */
    public function sliceFinder($offset, $length = null);

    /**
     * @param ComparatorInterface $comparator
     *
     * @return FinderInterface
     */
    public function sortedFinder(ComparatorInterface $comparator);
}
