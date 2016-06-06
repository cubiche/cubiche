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

/**
 * QueryRepository interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface QueryRepositoryInterface extends RepositoryInterface, CollectionInterface
{
    /**
     * Find the first element that match with a given specification in this collection.
     *
     * @param SpecificationInterface $criteria
     *
     * @return mixed
     */
    public function findOne(SpecificationInterface $criteria);
}
