<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\DataSource;

use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Comparable\ComparatorInterface;

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
     * @see \Cubiche\Core\Collections\DataSource\DataSourceInterface::length()
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\DataSource\DataSourceInterface::offset()
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\DataSource\DataSourceInterface::searchCriteria()
     */
    public function searchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\DataSource\DataSourceInterface::sortCriteria()
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
     * @return bool
     */
    public function isFiltered()
    {
        return $this->searchCriteria() !== null;
    }

    /**
     * @return bool
     */
    public function isSliced()
    {
        return $this->offset() !== null || $this->length() !== null;
    }

    /**
     * @param int $offset
     */
    protected function actualOffset($offset)
    {
        $actualOffset = (int) $offset;
        if ($this->offset() !== null) {
            $actualOffset += $this->offset();
        }

        return $actualOffset;
    }

    /**
     * @param int $offset
     * @param int $length
     */
    protected function actualLength($offset, $length = null)
    {
        $actualLength = $length;
        if ($this->isSliced()) {
            if ($this->length() !== null && $offset >= $this->length()) {
                return 0;
            }
            if ($this->length() !== null) {
                $actualLength = $this->length() - (int) $offset;
                if ($length !== null) {
                    $actualLength = \min([$actualLength, $length]);
                }
            }
        }

        return $actualLength;
    }

    /**
     * @return int
     */
    abstract protected function calculateCount();
}
