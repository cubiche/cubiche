<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository;

use Cubiche\Domain\EventSourcing\AggregateRootInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * Repository Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface RepositoryInterface
{
    /**
     * Find one element by a given id in the collection.
     *
     * @param IdInterface $id
     *
     * @return AggregateRootInterface
     */
    public function get(IdInterface $id);

    /**
     * Persist the element in the collection.
     *
     * @param AggregateRootInterface $element
     */
    public function persist(AggregateRootInterface $element);

    /**
     * Persist all elements in the collection.
     *
     * @param AggregateRootInterface[] $elements
     */
    public function persistAll($elements);

    /**
     * Remove a given element from the collection.
     *
     * @param AggregateRootInterface $element
     */
    public function remove(AggregateRootInterface $element);
}
