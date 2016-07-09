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

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\Tests\Fixtures\Value;

/**
 * Comparator Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ComparatorTestCase extends ComparatorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        return array(
            array(1, 2, -1),
            array(new Value(1), 1, 0),
            array(1, new Value(0), 1),
            array(new Value(1), new Value(2), -1),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function newDefaultOtherwiseComparator()
    {
        return new Comparator();
    }
}
