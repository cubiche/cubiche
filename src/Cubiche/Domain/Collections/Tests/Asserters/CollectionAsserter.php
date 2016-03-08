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
use mageekguy\atoum\asserters\object as Object;

/**
 * CollectionAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CollectionAsserter extends Object implements CollectionAsserterInterface
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
     * @return mixed
     */
    public function size()
    {
        return $this->generator->__call(
            'integer',
            array($this->valueIsSet()->value->count())
        );
    }

    /**
     * @param string $failMessage
     *
     * @return $this
     */
    public function isEmpty($failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->count()) === 0) {
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
        if ($this->valueIsSet()->value->count() > 0) {
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
            if (self::isCollection($this->value) === false) {
                $this->fail($this->getLocale()->_('%s is not an collection', $this));
            } else {
                $this->pass();
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
        $collection = $this->valueIsSet()->value;
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
        } else {
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
        $collection = $this->valueIsSet()->value;
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
        $collection = $this->valueIsSet()->value;
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
     * @param $value
     *
     * @return bool
     */
    protected static function isCollection($value)
    {
        return $value instanceof CollectionInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function valueIsSet($message = 'Collection is undefined')
    {
        return parent::valueIsSet($message);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function contains($value)
    {
        $collection = $this->valueIsSet()->value;
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->pass();
        } else {
            $this->fail($this->getLocale()->_('The collection not contain the value %s', $value));
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
        $collection = $this->valueIsSet()->value;
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->fail($this->getLocale()->_('The collection contain an element with this value %s', $value));
        } else {
            $this->pass();
        }

        return $this;
    }
}
