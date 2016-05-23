<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection\Tests\Asserters;

use Cubiche\Core\Collection\SetInterface;
use Cubiche\Core\Specification\Criteria;
use mageekguy\atoum\exceptions\logic as LogicException;

/**
 * SetAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SetAsserter extends CollectionAsserter
{
    /**
     * @var bool
     */
    protected $assertAll;

    /**
     * {@inheritdoc}
     */
    public function setWith($value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if ($checkType === true) {
            if ($this->value instanceof SetInterface) {
                $this->pass();
            } else {
                $this->fail($this->getLocale()->_('%s is not a list', $this));
            }
        }

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function contains($value)
    {
        $collection = $this->valueAsCollection();
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->pass();
        } else {
            $this->fail($this->getLocale()->_('The set not contain the value %s', $value));
        }

        return $this;
    }

    /**
     * @param array|\Traversable $values
     *
     * @return $this
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
     * @return $this
     */
    public function notContains($value)
    {
        $collection = $this->valueAsCollection();
        if ($collection->findOne(Criteria::eq($value)) !== null) {
            $this->fail($this->getLocale()->_('The set contain an element with this value %s', $value));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @throws LogicException
     *
     * @return \Cubiche\Core\Collection\SetInterface
     */
    protected function valueAsCollection()
    {
        $value = $this->valueIsSet()->getValue();
        if ($value instanceof SetInterface) {
            return $value;
        }

        throw new LogicException('Set is undefined');
    }
}
