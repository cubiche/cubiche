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

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * QueryRepository interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface QueryRepositoryInterface extends CollectionInterface
{
    /**
     * Find one element by a given id in the collection.
     *
     * @param IdInterface $id
     *
     * @return ReadModelInterface
     */
    public function get(IdInterface $id);

    /**
     * Persist the element in the collection.
     *
     * @param ReadModelInterface $element
     */
    public function persist(ReadModelInterface $element);

    /**
     * Persist all elements in the collection.
     *
     * @param ReadModelInterface[] $elements
     */
    public function persistAll($elements);

    /**
     * Remove a given element from the collection.
     *
     * @param ReadModelInterface $element
     */
    public function remove(ReadModelInterface $element);

    /**
     * Find the first element that match with a given specification in this collection.
     *
     * @param SpecificationInterface $criteria
     *
     * @return ReadModelInterface
     */
    public function findOne(SpecificationInterface $criteria);
}
