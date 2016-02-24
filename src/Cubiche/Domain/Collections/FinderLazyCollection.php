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

use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * Finder Lazy Collection.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FinderLazyCollection extends LazyCollection
{
    /**
     * @var FinderInterface
     */
    protected $finder;

    /**
     * @param FinderInterface $finder
     */
    public function __construct(FinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::initialize()
     */
    protected function initialize()
    {
        $this->collection = new ArrayCollection();

        foreach ($this->finder->getIterator() as $item) {
            $this->collection->add($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::count()
     */
    public function count()
    {
        if ($this->isInitialized()) {
            return parent::count();
        }

        return $this->finder->count();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::getIterator()
     */
    public function getIterator()
    {
        if ($this->isInitialized()) {
            return parent::getIterator();
        }

        return $this->finder->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::slice()
     */
    public function slice($offset, $length = null)
    {
        if ($this->isInitialized()) {
            return parent::slice($offset, $length);
        }

        return new self($this->finder->sliceFinder($offset, $length));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\LazyCollection::sorted()
     */
    public function sorted(ComparatorInterface $comparator)
    {
        if ($this->isInitialized()) {
            return parent::sorted($comparator);
        }

        return new self($this->finder->sortedFinder($comparator));
    }
}
