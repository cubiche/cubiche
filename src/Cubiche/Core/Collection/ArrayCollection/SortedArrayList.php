<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\ArrayCollection;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;

/**
 * SortedArrayList Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayList extends ArrayList
{
    /**
     * @var ComparatorInterface
     */
    protected $criteria;

    /**
     * SortedArrayList constructor.
     *
     * @param array                    $elements
     * @param ComparatorInterface|null $criteria
     */
    public function __construct(array $elements = array(), ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        parent::__construct($elements);
        $this->sort($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        parent::add($element);

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($elements)
    {
        $this->validateTraversable($elements);

        foreach ($elements as $element) {
            $this->elements[] = $element;
        }

        $this->sort();
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
    public function removeAt($key)
    {
        $removed = parent::removeAt($key);
        if ($removed !== null) {
            $this->sort();
        }

        return $removed;
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
}
