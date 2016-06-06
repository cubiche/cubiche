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
 * SortedArrayHashMap Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SortedArrayHashMap extends ArrayHashMap
{
    /**
     * @var ComparatorInterface
     */
    protected $criteria;

    /**
     * SortedArrayHashMap constructor.
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
        foreach ($elements as $key => $element) {
            parent::set($key, $element);
        }

        $this->sort();
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        $this->sort();
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
