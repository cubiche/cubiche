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

use Cubiche\Core\Selector\Key;
use Cubiche\Core\Comparable\Order;

/**
 * Selector Comparator Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparatorTests extends AbstractComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitSelectorComparator';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(new Key('foo'), Order::ASC());
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        return array(
            array(array('foo' => 1), array('foo' => 2), -1),
            array(array('foo' => 1), array('foo' => 1), 0),
            array(array('foo' => 1), array('foo' => 0), 1),
        );
    }
}
