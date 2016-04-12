<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\DataSource;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Data Source Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface DataSourceInterface extends \Countable, \IteratorAggregate
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
    public function searchCriteria();

    /**
     * @return \Cubiche\Domain\Comparable\ComparatorInterface
     */
    public function sortCriteria();

    /**
     * @return mixed
     */
    public function findOne();

    /**
     * @param SpecificationInterface $criteria
     *
     * @return DataSourceInterface
     */
    public function filteredDataSource(SpecificationInterface $criteria);

    /**
     * @param int $offset
     * @param int $length
     *
     * @return DataSourceInterface
     */
    public function slicedDataSource($offset, $length = null);

    /**
     * @param ComparatorInterface $sortCriteria
     *
     * @return DataSourceInterface
     */
    public function sortedDataSource(ComparatorInterface $sortCriteria);
}
