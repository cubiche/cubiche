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
class ArrayFinder extends Finder
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var Evaluator
     */
    protected $evaluator;

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
        parent::__construct($specification, $offset, $length);
        $this->items = $items;
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

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\FinderInterface::sliceFinder()
     */
    public function sliceFinder($offset, $length = null)
    {
        return new self($this->items, $this->specification, $offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Finder::calculateCount()
     */
    protected function calculateCount()
    {
        return \iterator_count($this->getIterator());
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
