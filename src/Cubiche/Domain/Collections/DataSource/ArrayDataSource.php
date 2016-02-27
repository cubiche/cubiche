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

use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Evaluator\EvaluatorBuilder;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\Selector\This;

/**
 * Array Data Source Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ArrayDataSource extends DataSource
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var \Cubiche\Domain\Specification\Evaluator\Evaluator
     */
    protected $evaluator;

    /**
     * @var bool
     */
    private $sorted;

    /**
     * @param array                  $items
     * @param SpecificationInterface $searchCriteria
     * @param ComparatorInterface    $sortCriteria
     * @param int                    $offset
     * @param int                    $length
     * @param bool                   $sorted
     */
    public function __construct(
        array $items,
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null,
        $sorted = false
    ) {
        parent::__construct($searchCriteria, $sortCriteria, $offset, $length);

        $this->items = $items;
        $this->sorted = $sorted;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        $this->sort();

        $count = 0;
        $offset = 0;
        foreach ($this->items as $item) {
            if (!$this->checkLenght($count)) {
                break;
            }
            if ($this->evaluator()->evaluate($item) === true) {
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
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::findOne()
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
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::filteredDataSource()
     */
    public function filteredDataSource(SpecificationInterface $criteria)
    {
        if ($this->isFiltered()) {
            $criteria = $this->searchCriteria()->andX($criteria);
        }

        return new self(
            $this->items,
            $criteria,
            $this->sortCriteria(),
            $this->offset(),
            $this->length(),
            $this->sorted
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::slicedDataSource()
     */
    public function slicedDataSource($offset, $length = null)
    {
        return new self(
            $this->items,
            $this->searchCriteria(),
            $this->sortCriteria(),
            $this->actualOffset($offset),
            $this->actualLength($offset, $length),
            $this->sorted
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSourceInterface::sortedDataSource()
     */
    public function sortedDataSource(ComparatorInterface $sortCriteria)
    {
        return new self($this->items, $this->searchCriteria(), $sortCriteria, $this->offset(), $this->length(), false);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\DataSource\DataSource::calculateCount()
     */
    protected function calculateCount()
    {
        return \iterator_count($this->getIterator());
    }

    /**
     * @return \Cubiche\Domain\Specification\Evaluator\Evaluator
     */
    protected function evaluator()
    {
        if ($this->evaluator === null) {
            $evaluatorBuilder = new EvaluatorBuilder();
            $criteria = $this->searchCriteria();
            if ($criteria === null) {
                $criteria = Criteria::true();
            }

            $this->evaluator = $evaluatorBuilder->evaluator($criteria);
        }

        return $this->evaluator;
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
        if (!$this->sorted && $this->isSorted()) {
            usort($this->items, function ($a, $b) {
                return $this->sortCriteria()->compare($a, $b);
            });
            $this->sorted = true;
        }
    }
}
