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
use Cubiche\Core\Comparable\Custom;

/**
 * MultiComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MultiComparatorTests extends AbstractComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        $comparator = new Comparator();
        $custom = new Custom(function ($a, $b) use ($comparator) {
            return $comparator->compare($a % 2, $b % 2);
        });

        return array($custom, $comparator);
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        return array(
            array(1, 2, 1),
            array(4, 1, -1),
            array(1, 3, -1),
            array(4, 2, 1),
            array(1, 1, 0),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitMultiComparator';
    }
}
