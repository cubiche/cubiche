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
use Cubiche\Domain\Specification\SpecificationInterface;
use mageekguy\atoum\asserters\object as Object;

/**
 * Mock class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Collection extends Object
{
    /**
     * @var SpecificationInterface
     */
    protected $searchCriteria;

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
            default:
                return parent::__get($asserter);
        }
    }

    /**
     * @return mixed
     */
    protected function size()
    {
        return $this->generator->__call(
            'integer',
            array(count($this->valueIsSet()->value->toArray()))
        );
    }

    /**
     * @param string $failMessage
     *
     * @return $this
     */
    public function isEmpty($failMessage = null)
    {
        if (($actual = count($this->valueIsSet()->value->toArray())) === 0) {
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
        if (count($this->valueIsSet()->value->toArray()) > 0) {
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
     * @param SpecificationInterface $searchCriteria
     *
     * @return $this
     */
    public function setSearchCriteria(SpecificationInterface $searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;

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
     * @return bool
     */
    protected function isFiltered()
    {
        return $this->searchCriteria !== null;
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
     * @param CollectionInterface $collection
     *
     * @return mixed
     */
    protected function getCollectionAsserter(CollectionInterface $collection)
    {
        return $this->generator->__call('Collection', array($collection));
    }

    /**
     * @param SpecificationInterface $criteria
     *
     * @return mixed
     */
    public function find(SpecificationInterface $criteria)
    {
        $filteredCollection = $this->getCollectionAsserter($this->valueIsSet()->value->find($criteria));
        $filteredCollection->setSearchCriteria($criteria);

        return $filteredCollection;
    }
}
