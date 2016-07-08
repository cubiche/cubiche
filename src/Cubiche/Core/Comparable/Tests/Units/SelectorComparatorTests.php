<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Units;

use Cubiche\Core\Comparable\Order;

/**
 * Selector Comparator Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(
            function ($value) {
                return $value['foo'];
            },
            Order::ASC(),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        return array(
            array(array('foo' => 1, 'bar' => 2), array('foo' => 2, 'bar' => 1), -1),
            array(array('foo' => 1, 'bar' => 0), array('foo' => 1, 'bar' => 2), 0),
            array(array('foo' => 1, 'bar' => 2), array('foo' => 0, 'bar' => 1), 1),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function newDefaultOtherwiseComparator()
    {
        return function ($value) {
            return $value['bar'];
        };
    }
}
