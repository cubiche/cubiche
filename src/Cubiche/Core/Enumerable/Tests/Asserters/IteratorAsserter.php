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

use mageekguy\atoum\asserters\iterator as BaseIteratorAsserter;

/**
 * Iterator Asserter class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class IteratorAsserter extends BaseIteratorAsserter
{
    /**
     * {@inheritdoc}
     */
    public function __get($asserter)
    {
        switch (\strtolower($asserter)) {
            case 'count':
                return $this->size();
            default:
                return parent::__get($asserter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo($value, $failMessage = null)
    {
        $this->compare($this->valueIsSet()->getValue(), $value, true, $failMessage);

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
        $this->compare($this->valueIsSet()->getValue(), $iterator, false, $failMessage);

        return $this;
    }

    /**
     * @param \Iterator $result
     * @param \Iterator $expected
     * @param bool      $compareKeys
     * @param string    $failMessage
     */
    protected function compare(\Iterator $result, \Iterator $expected, $compareKeys = false, $failMessage = null)
    {
        /* @var \mageekguy\atoum\stubs\asserters\phpArray $asserter */
        $arrayAsserter = $this->generator->__call('array', array(\iterator_to_array($result, $compareKeys)));
        $arrayAsserter->isEqualTo(\iterator_to_array($expected, $compareKeys), $failMessage);
    }
}
