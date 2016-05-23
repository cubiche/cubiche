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

/**
 * Collection Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * Clears the collection, removing all elements.
     */
    public function clear();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray();

    /**
     * @param ComparatorInterface $criteria
     *
     * @return CollectionInterface
     */
    public function sorted(ComparatorInterface $criteria);
}
