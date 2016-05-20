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

use Cubiche\Core\Collection\DataSource\ArrayDataSource;
use Cubiche\Core\Collection\DataSourceList;
use Cubiche\Core\Collection\Exception\InvalidKeyException;
use Cubiche\Core\Collection\ListInterface;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * ArrayList Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayList extends ArrayCollection implements ListInterface
{
    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
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
    public function remove($element)
    {
        $criteria = Criteria::eq($element);
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
    public function removeAt($key)
    {
        if ($this->hasKey($key)) {
            $removed = $this->elements[$key];
            unset($this->elements[$key]);
            $this->elements = array_values($this->elements);

            return $removed;
        }

        return;
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
    public function indexOf($element)
    {
        $criteria = Criteria::eq($element);
        foreach ($this->elements as $key => $value) {
            if ($criteria->evaluate($value)) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * {@inheritdoc}
     */
    public function subList($offset, $length = null)
    {
        return new self(\array_slice($this->elements, $offset, $length, true));
    }

    /**
     * {@inheritdoc}
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        uasort($this->items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceList(new ArrayDataSource($this->elements, $criteria));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new ArrayDataSource($this->items, $criteria))->findOne();
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

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->removeAt($offset);
    }
}
