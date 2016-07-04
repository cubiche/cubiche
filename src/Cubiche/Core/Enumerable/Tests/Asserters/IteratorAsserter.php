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
        $result->rewind();
        $expected->rewind();
        while ($result->valid() && $expected->valid()) {
            $this->areEquals($result->current(), $expected->current());
            if ($compareKeys) {
                $this->areEquals($result->key(), $expected->key());
            }
            $result->next();
            $expected->next();
        }
        $this->areEquals($result->valid(), $expected->valid(), $failMessage);
    }

    /**
     * @param mixed  $value
     * @param mixed  $expected
     * @param string $failMessage
     */
    private function areEquals($value, $expected, $failMessage = null)
    {
        /** @var \mageekguy\atoum\stubs\asserters\variable $asserter */
        $asserter = $this->generator->__call('variable', array($value));
        $asserter->isEqualTo($expected, $failMessage);
    }
}
