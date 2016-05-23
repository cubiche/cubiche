<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\ArrayCollection;

use Cubiche\Core\Collection\SetInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * ArraySet interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ArraySetInterface extends SetInterface
{
    /**
     * Returns true if this set contains the specified element.
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element);

    /**
     * Returns true if this set contains all of the elements of the specified collection.
     *
     * @param array|\Traversable $elements
     *
     * @return bool
     */
    public function containsAll($elements);

    /**
     * Sorts the set elements according to the order induced by the specified comparator.
     *
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);
}
