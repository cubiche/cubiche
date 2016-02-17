<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Collections\Specification\Evaluator\EvaluatorVisitor;
use Cubiche\Domain\Collections\Specification\Evaluator\Evaluator;

/**
 * ArrayFinder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ArrayFinder implements FinderInterface
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var Evaluator
     */
    protected $evaluator;

    /**
     * @var int
     */
    protected $count;

    /**
     * @param array                  $items
     * @param SpecificationInterface $specification
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        array $items,
        SpecificationInterface $specification,
        $offset = null,
        $length = null
    ) {
        $this->items = $items;
        $this->specification = $specification;
        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
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
     * @param int $offset
     *
     * @return bool
     */
    protected function checkOffset($offset)
    {
        return $this->offset === null || $offset === $this->offset;
    }

    /**
     * @param int $count
     *
     * @return bool
     */
    protected function checkLenght($count)
    {
        return $this->length === null || $count < $this->length;
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return FinderInterface
     */
    public function sliceFinder($offset, $length = null)
    {
        return new self($this->items, $this->specification, $offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        if ($this->count === null) {
            $this->count = \iterator_count($this->getIterator());
        }

        return $this->count;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\Evaluator\Evaluator
     */
    protected function evaluator()
    {
        if ($this->evaluator === null) {
            $evaluatorVisitor = new EvaluatorVisitor();
            $this->evaluator = $evaluatorVisitor->evaluator($this->specification);
        }

        return $this->evaluator;
    }
}
