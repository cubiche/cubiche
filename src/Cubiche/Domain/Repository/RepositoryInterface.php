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

use Cubiche\Domain\Model\IdInterface;

/**
 * Repository Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface RepositoryInterface extends \IteratorAggregate
{
    /**
     * Find one element by a given id in the collection.
     *
     * @param IdInterface $id
     *
     * @return mixed
     */
    public function get(IdInterface $id);

    /**
     * Persist the element in the collection.
     *
     * @param mixed $element
     */
    public function persist($element);

    /**
     * Persist all elements in the collection.
     *
     * @param array|\Traversable $elements
     */
    public function persistAll($elements);

    /**
     * Remove a given element from the collection.
     *
     * @param mixed $element
     */
    public function remove($element);
}
