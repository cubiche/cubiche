<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Asserters;

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;
use mageekguy\atoum\asserters\object as ObjectAsserter;
use mageekguy\atoum\exceptions\logic as LogicException;

/**
 * CollectionAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CollectionAsserter extends ObjectAsserter
{
    /**
     * @var bool
     */
    protected $assertAll;

    /**
     * {@inheritdoc}
     */
    public function __get($asserter)
    {
        switch (strtolower($asserter)) {
            case 'size':
                return $this->size();
            case 'isempty':
                return $this->isEmpty();
            case 'isnotempty':
                return $this->isNotEmpty();
            case 'hasallelements':
                return $this->hasAllElements();
            case 'hasnoelements':
                return $this->hasNoElements();
            case 'issorted':
                return $this->isSorted();
            case 'isnotsorted':
                return $this->isNotSorted();
            default:
                return parent::__get($asserter);
        }
    }

    /**
     * @return \mageekguy\atoum\stubs\asserters\integer
     */
    public function size()
    {
        return $this->generator->__call(
            'integer',
            array($this->valueAsCollection()->count())
        );
    }

    /**
     * @param string $failMessage
     *
     * @return $this
     */
    public function isEmpty($failMessage = null)
    {
        if (($actual = $this->valueAsCollection()->count()) === 0) {
            $this->pass();
        } else {
            $this->fail($failMessage ?: $this->getLocale()->_('%s is not empty', $this, $actual));
        }

        return $this;
    }

    /**
     * @param string $failMessage
     *
     * @return $this
     */
    public function isNotEmpty($failMessage = null)
    {
        if ($this->valueAsCollection()->count() > 0) {
            $this->pass();
        } else {
            $this->fail($failMessage ?: $this->_('%s is empty', $this));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function hasAllElements()
    {
        $this->assertAll = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function hasNoElements()
    {
        $this->assertAll = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setWith($value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if ($checkType === true) {
            if ($this->value instanceof CollectionInterface) {
                $this->pass();
            } else {
                $this->fail($this->getLocale()->_('%s is not a collection', $this));
            }
        }

        return $this;
    }

    /**
     * @param SpecificationInterface $criteria
     *
     * @return $this
     */
    public function thatMatchToCriteria(SpecificationInterface $criteria)
    {
        $collection = $this->valueAsCollection();
        foreach ($collection as $item) {
            if (!$this->checkMatchResult($criteria->evaluate($item))) {
                return $this;
            }
        }

        $this->pass();

        return $this;
    }

    /**
     * @param bool $result
     *
     * @return bool
     */
    private function checkMatchResult($result)
    {
        if ($result === true && $this->assertAll === false) {
            $this->fail($this->_('At least one items that match with the given criteria'));

            return false;
        }
        if ($result === false && $this->assertAll === true) {
            $this->fail($this->_('At least one items that not match with the given criteria'));

            return false;
        }

        return true;
    }

    /**
     * @return $this
     */
    public function isSorted()
    {
        return $this->isSortedUsing(new Comparator());
    }

    /**
     * @param ComparatorInterface $comparator
     *
     * @return $this
     */
    public function isSortedUsing(ComparatorInterface $comparator)
    {
        list($item1, $item2) = $this->checkIsSorted($comparator);
        if ($item1 !== null && $item2 !== null) {
            $this->fail(
                $this->_("There are items [%s, %s] that aren't ordered in the given collection", $item1, $item2)
            );
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isNotSorted()
    {
        return $this->isNotSortedUsing(new Comparator());
    }

    /**
     * @param ComparatorInterface $comparator
     *
     * @return $this
     */
    public function isNotSortedUsing(ComparatorInterface $comparator)
    {
        list($item1, $item2) = $this->checkIsSorted($comparator);
        if ($item1 !== null && $item2 !== null) {
            $this->fail($this->_('The given collection is sorted'));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param ComparatorInterface $comparator
     *
     * @return array
     */
    private function checkIsSorted(ComparatorInterface $comparator)
    {
        $collection = $this->valueAsCollection();
        $last = null;
        foreach ($collection as $item) {
            if ($last !== null && $comparator->compare($last, $item) > 0) {
                return array($last, $item);
            }
        }

        return array(null, null);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function contains($value)
    {
        $collection = $this->valueAsCollection();
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->pass();
        } else {
            $this->fail($this->getLocale()->_('The collection not contain the value %s', $value));
        }

        return $this;
    }

    /**
     * @param array|\Traversable $values
     *
     * @return mixed
     */
    public function containsValues($values)
    {
        foreach ($values as $value) {
            $this->contains($value);
        }

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function notContains($value)
    {
        $collection = $this->valueAsCollection();
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->fail($this->getLocale()->_('The collection contain an element with this value %s', $value));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function valueIsSet($message = 'Collection is undefined')
    {
        return parent::valueIsSet($message);
    }

    /**
     * @throws LogicException
     *
     * @return \Cubiche\Core\Collections\CollectionInterface
     */
    protected function valueAsCollection()
    {
        $value = $this->valueIsSet()->getValue();
        if ($value instanceof CollectionInterface) {
            return $value;
        }

        throw new LogicException('Collection is undefined');
    }
}
