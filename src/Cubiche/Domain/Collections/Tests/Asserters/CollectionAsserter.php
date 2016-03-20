<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Asserters;

use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\SpecificationInterface;
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
     *
     * @see \mageekguy\atoum\asserters\object::setWith()
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
        $passed = 0;
        $failed = 0;
        $collection = $this->valueAsCollection();
        foreach ($collection as $item) {
            if ($criteria->evaluate($item)) {
                ++$passed;
            } else {
                ++$failed;
            }
        }

        if ($this->assertAll === true) {
            if ($failed == 0) {
                $this->pass();
            } else {
                $this->fail($this->_('There is %s items that not match with the given criteria', $failed));
            }
        } elseif ($this->assertAll === false) {
            if ($passed == 0) {
                $this->pass();
            } else {
                $this->fail($this->_('There is %s items that match with the given criteria', $failed));
            }
        }

        return $this;
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
        $collection = $this->valueAsCollection();
        $last = null;
        foreach ($collection as $item) {
            if ($last !== null) {
                if ($comparator->compare($last, $item) > 0) {
                    $this->fail(
                        $this->_("There are items [%s, %s] that aren't ordered in the given collection", $last, $item)
                    );

                    return $this;
                }
            }

            $last = $item;
        }

        $this->pass();

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
        $collection = $this->valueAsCollection();
        $last = null;
        $unsorted = 0;
        foreach ($collection as $item) {
            if ($last !== null) {
                if ($comparator->compare($last, $item) > 0) {
                    ++$unsorted;
                }
            }

            $last = $item;
        }

        if ($unsorted > 0) {
            $this->pass();
        } else {
            $this->fail($this->_('The given collection is sorted'));
        }

        return $this;
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
     *
     * @see \mageekguy\atoum\asserters\object::valueIsSet()
     */
    protected function valueIsSet($message = 'Collection is undefined')
    {
        return parent::valueIsSet($message);
    }

    /**
     * @throws LogicException
     *
     * @return \Cubiche\Domain\Collections\CollectionInterface
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
