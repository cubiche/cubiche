<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\DataSource;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Iterator Data Source Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IteratorDataSource extends DataSource
{
    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * @var bool
     */
    private $iteratorSorted;

    /**
     * @param \Traversable           $iterator
     * @param SpecificationInterface $searchCriteria
     * @param ComparatorInterface    $sortCriteria
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        \Traversable $iterator,
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        parent::__construct($searchCriteria, $sortCriteria, $offset, $length);

        $this->iterator = $iterator;
        $this->iteratorSorted = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $this->sort();

        $count = 0;
        $offset = 0;
        foreach ($this->iterator as $item) {
            if (!$this->checkLenght($count)) {
                break;
            }
            if ($this->evaluate($item) === true) {
                if ($this->checkOffset($offset)) {
                    ++$count;
                    yield $item;
                } else {
                    ++$offset;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        $iterator = $this->getIterator();
        $iterator->rewind();

        if ($iterator->valid()) {
            return $iterator->current();
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $last = false;
        $iterator = $this->getIterator();

        while ($iterator->valid()) {
            $last = $iterator->current();

            $iterator->next();
        }

        return $last;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $iterator = $this->getIterator();
        $iterator->next();

        if ($iterator->valid()) {
            return $iterator->current();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->getIterator()->current();
    }

    /**
     * {@inheritdoc}
     */
    public function findOne()
    {
        foreach ($this->getIterator() as $item) {
            return $item;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function filteredDataSource(SpecificationInterface $criteria)
    {
        if ($this->isFiltered()) {
            $criteria = $this->searchCriteria()->andX($criteria);
        }

        return new self(
            $this->iterator,
            $criteria,
            $this->iteratorSorted ? null : $this->sortCriteria(),
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function slicedDataSource($offset, $length = null)
    {
        return new self(
            $this->iterator,
            $this->searchCriteria(),
            $this->iteratorSorted ? null : $this->sortCriteria(),
            $this->actualOffset($offset),
            $this->actualLength($offset, $length)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sortedDataSource(ComparatorInterface $sortCriteria)
    {
        return new self(
            $this->iterator,
            $this->searchCriteria(),
            $sortCriteria,
            $this->offset(),
            $this->length()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function calculateCount()
    {
        return \iterator_count($this->getIterator());
    }

    /**
     * @param mixed $item
     *
     * @return bool
     */
    protected function evaluate($item)
    {
        return $this->searchCriteria() === null || $this->searchCriteria()->evaluate($item);
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    private function checkOffset($offset)
    {
        return $this->offset === null || $offset === $this->offset;
    }

    /**
     * @param int $count
     *
     * @return bool
     */
    private function checkLenght($count)
    {
        return $this->length === null || $count < $this->length;
    }

    private function sort()
    {
        if (!$this->iteratorSorted && $this->isSorted()) {
            if (!($this->iterator instanceof \ArrayIterator)) {
                $this->iterator = new \ArrayIterator(iterator_to_array($this->iterator));
            }
            $this->iterator->uasort(function ($a, $b) {
                return $this->sortCriteria()->compare($a, $b);
            });

            $this->iteratorSorted = true;
        }
    }
}
