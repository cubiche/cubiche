<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Enumerable\Tests\Asserters;

use Cubiche\Core\Enumerable\EnumerableInterface;
use mageekguy\atoum\asserters\phpObject as ObjectAsserter;
use Cubiche\Core\Equatable\EqualityComparer;

/**
 * Enumerable Asserter class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EnumerableAsserter extends ObjectAsserter
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
        switch (\strtolower($asserter)) {
            case 'count':
                return $this->count();
            default:
                return parent::__get($asserter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWith($value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if ($checkType === true) {
            if ($this->value instanceof EnumerableInterface) {
                $this->pass();
            } else {
                $this->fail($this->getLocale()->_('%s is not an enumerable', $this));
            }
        }

        return $this;
    }

    /**
     * @return \mageekguy\atoum\stubs\asserters\integer
     */
    public function count()
    {
        return $this->generator->__call(
            'integer',
            array($this->valueAsEnumerable()->count())
        );
    }

    /**
     * @return IteratorAsserter
     */
    public function getIterator()
    {
        return $this->generator->__call(
            'iterator',
            array($this->valueAsEnumerable()->getIterator())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty($failMessage = null)
    {
        $this->getIterator()->isEmpty($failMessage);

        return $this;
    }

    /**
     * @param unknown $failMessage
     *
     * @return $this
     */
    public function isNotEmpty($failMessage = null)
    {
        $this->getIterator()->isNotEmpty($failMessage);

        return $this;
    }

    /**
     * @param \Iterator $iterator
     * @param string    $failMessage
     *
     * @return $this
     */
    public function valuesAreEqualTo(\Iterator $iterator, $failMessage = null)
    {
        $this->getIterator()->valuesAreEqualTo($iterator, $failMessage);

        return $this;
    }

    /**
     * @param \Iterator $iterator
     * @param string    $failMessage
     *
     * @return $this
     */
    public function iteratorAreEqualTo(\Iterator $iterator, $failMessage = null)
    {
        $this->getIterator()->isEqualTo($iterator, $failMessage);

        return $this;
    }

    /**
     * @param callable $equalityComparer
     * @param string   $failMessage
     *
     * @return $this
     */
    public function areDistinct(callable $equalityComparer = null, $failMessage = 'There are duplicate elements')
    {
        $equalityComparer = EqualityComparer::ensure($equalityComparer);
        $array = $this->valueAsEnumerable()->toArray();
        for ($i = 0; $i < \count($array) - 1; ++$i) {
            for ($j = $i + 1; $j < \count($array); ++$j) {
                if ($equalityComparer->equals($array[$i], $array[$j])) {
                    $this->fail($failMessage);
                }
            }
        }
        $this->pass();

        return $this;
    }

    /**
     * @param mixed    $value
     * @param callable $equalityComparer
     * @param string   $failMessage
     *
     * @return $this
     */
    public function contains(
        $value,
        callable $equalityComparer = null,
        $failMessage = 'The value is not contained in the sequense'
    ) {
        if ($this->valueAsEnumerable()->contains($value, $equalityComparer)) {
            $this->pass();
        } else {
            $this->fail($failMessage);
        }

        return $this;
    }

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     * @param string             $failMessage
     *
     * @return $this
     */
    public function containsAll(
        $enumerable,
        callable $equalityComparer = null,
        $failMessage = 'The sequense not contains all values'
    ) {
        foreach ($enumerable as $value) {
            if (!$this->valueAsEnumerable()->contains($value, $equalityComparer)) {
                //                 var_export($this->valueAsEnumerable());
//                 var_export($enumerable);
//                 var_export($value);
                $this->fail($failMessage);
            }
        }
        $this->pass();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function valueIsSet($message = 'Enumerable is undefined')
    {
        return parent::valueIsSet($message);
    }

    /**
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    protected function valueAsEnumerable()
    {
        /** @var \Cubiche\Core\Enumerable\EnumerableInterface $value */
        $value = $this->valueIsSet()->getValue();

        return $value;
    }
}
