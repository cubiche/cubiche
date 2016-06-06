<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\ArrayCollection;

use Cubiche\Core\Collections\ListInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * ArrayList interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ArrayListInterface extends ListInterface
{
    /**
     * Removes the element at the specified position in this list.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function removeAt($key);

    /**
     * Returns true if this collection contains the specified element.
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element);

    /**
     * Returns the index of the first occurrence of the specified element in this list,
     * or -1 if this list does not contain the element.
     *
     * @param mixed $element
     *
     * @return int
     */
    public function indexOf($element);

    /**
     * Sorts the list alements according to the order induced by the specified comparator.
     *
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);
}
