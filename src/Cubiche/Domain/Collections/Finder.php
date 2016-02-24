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

use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Finder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Finder implements FinderInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * @var ComparatorInterface
     */
    protected $comparator;

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
     * @param SpecificationInterface $specification
     * @param ComparatorInterface    $comparator
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        SpecificationInterface $specification = null,
        ComparatorInterface $comparator = null,
        $offset = null,
        $length = null
    ) {
        $this->specification = $specification;
        $this->comparator = $comparator;
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
     * @see \Cubiche\Domain\Collections\FinderInterface::length()
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\FinderInterface::offset()
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\FinderInterface::specification()
     */
    public function specification()
    {
        return $this->specification;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\FinderInterface::comparator()
     */
    public function comparator()
    {
        return $this->comparator;
    }

    /**
     * @return bool
     */
    public function isSorted()
    {
        return $this->comparator !== null;
    }

    /**
     * @return int
     */
    abstract protected function calculateCount();
}
