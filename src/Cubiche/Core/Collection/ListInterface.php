<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * List interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ListInterface extends CollectionInterface
{
    /**
     * Appends the specified element to the end of this list.
     *
     * @param mixed $element
     */
    public function add($element);

    /**
     * Appends all of the elements in the specified collection to the end of this list.
     *
     * @param array|\Traversable $elements
     */
    public function addAll($elements);

    /**
     * Removes the first occurrence of the specified element from this list, if it is present.
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function remove($element);

    /**
     * Removes from this list all of its elements that are contained in the specified collection.
     * Returns true if this list changed as a result of the call.
     *
     * @param array|\Traversable $elements
     *
     * @return bool
     */
    public function removeAll($elements);

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
     * Returns a view of the portion of this list starting at the specified $offset, with size equals to $length.
     *
     * @param int $offset
     * @param int $length
     *
     * @return ListInterface
     */
    public function subList($offset, $length = null);

    /**
     * Sorts the list elements according to the order induced by the specified comparator.
     *
     * @param ComparatorInterface $criteria
     */
    public function sort(ComparatorInterface $criteria = null);

    /**
     * Find all elements that match with a given specification in this list.
     *
     * @param SpecificationInterface $criteria
     *
     * @return ListInterface
     */
    public function find(SpecificationInterface $criteria);

    /**
     * Find the first element that match with a given specification in this list.
     *
     * @param SpecificationInterface $criteria
     *
     * @return mixed
     */
    public function findOne(SpecificationInterface $criteria);
}
