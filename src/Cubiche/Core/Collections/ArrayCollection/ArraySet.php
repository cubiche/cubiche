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

use Cubiche\Core\Collections\DataSource\ArrayDataSource;
use Cubiche\Core\Collections\DataSourceSet;
use Cubiche\Core\Collections\Exception\InvalidKeyException;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * ArraySet Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArraySet extends ArrayCollection implements ArraySetInterface
{
    /**
     * ArraySet constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->addAll($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        $criteria = Criteria::eq($element);
        foreach ($this->elements as $key => $value) {
            if ($criteria->evaluate($value)) {
                $this->elements[$key] = $element;

                return;
            }
        }

        $this->elements[] = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($elements)
    {
        $this->validateTraversable($elements);

        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        $criteria = Criteria::eq($element);
        foreach ($this->elements as $key => $value) {
            if ($criteria->evaluate($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function containsAll($elements)
    {
        $this->validateTraversable($elements);

        foreach ($elements as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element)
    {
        $criteria = Criteria::same($element);
        foreach ($this->elements as $key => $value) {
            if ($criteria->evaluate($value)) {
                unset($this->elements[$key]);
                $this->elements = array_values($this->elements);

                return true;
            }
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
            if ($this->remove($element)) {
                $changed = true;
            }
        }

        return $changed;
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        uasort($this->elements, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceSet(new ArrayDataSource($this->elements, null, $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceSet(new ArrayDataSource($this->elements, $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new ArrayDataSource($this->elements, $criteria))->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function validateKey($key)
    {
        if (!is_int($key)) {
            throw InvalidKeyException::forKey($key);
        }

        return true;
    }
}
