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

use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Data Source Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DataSource implements DataSourceInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $searchCriteria;

    /**
     * @var ComparatorInterface
     */
    protected $sortCriteria;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    private $count;

    /**
     * @param SpecificationInterface $searchCriteria
     * @param ComparatorInterface    $sortCriteria
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        $this->searchCriteria = $searchCriteria;
        $this->sortCriteria = $sortCriteria;
        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        if ($this->count === null) {
            $this->count = $this->calculateCount();
        }

        return $this->count;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::length()
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::offset()
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::searchCriteria()
     */
    public function searchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::sortCriteria()
     */
    public function sortCriteria()
    {
        return $this->sortCriteria;
    }

    /**
     * @return bool
     */
    public function isSorted()
    {
        return $this->sortCriteria() !== null;
    }

    /**
     * @return int
     */
    abstract protected function calculateCount();
}
