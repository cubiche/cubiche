<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections;

use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Set interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SetInterface extends CollectionInterface
{
    /**
     * Adds the specified element to this set if it is not already present.
     *
     * @param mixed $element
     */
    public function add($element);

    /**
     * Adds all of the elements in the specified collection to this set if they're not already present.
     *
     * @param array|\Traversable $elements
     */
    public function addAll($elements);

    /**
     * Removes the specified element from this set if it is present .
     *
     * @param mixed $element
     *
     * @return bool
     */
    public function remove($element);

    /**
     * Removes from this set all of its elements that are contained in the specified collection.
     * Returns true if this set changed as a result of the call.
     *
     * @param array|\Traversable $elements
     *
     * @return bool
     */
    public function removeAll($elements);

    /**
     * Find the first element that match with a given specification in this set.
     *
     * @param SpecificationInterface $criteria
     *
     * @return mixed
     */
    public function findOne(SpecificationInterface $criteria);
}
