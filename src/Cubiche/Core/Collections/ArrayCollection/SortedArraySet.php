<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\ArrayCollection;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * SortedArraySet Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArraySet extends ArraySet
{
    /**
     * @var ComparatorInterface
     */
    protected $criteria;

    /**
     * SortedArraySet constructor.
     *
     * @param array                    $elements
     * @param ComparatorInterface|null $criteria
     */
    public function __construct(array $elements = array(), ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        $this->criteria = $criteria;
        parent::__construct($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        if (!$this->contains($element)) {
            $this->elements[] = $element;

            $this->sort();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($elements)
    {
        $this->validateTraversable($elements);

        $changed = false;
        foreach ($elements as $element) {
            if (!$this->contains($element)) {
                $this->elements[] = $element;
                $changed = true;
            }
        }

        if ($changed) {
            $this->sort();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element)
    {
        if (parent::remove($element)) {
            $this->sort();

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll($elements)
    {
        $this->validateTraversable($elements);

        $changed = false;
        foreach ($elements as $element) {
            if (parent::remove($element)) {
                $changed = true;
            }
        }

        if ($changed) {
            $this->sort();
        }

        return $changed;
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria !== null) {
            $this->criteria = $criteria;
        }

        parent::sort($this->criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);

        $this->sort();
    }
}
