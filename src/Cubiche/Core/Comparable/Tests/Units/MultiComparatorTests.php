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

/**
 * Multi Comparator Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MultiComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(new Comparator(), function ($a, $b) {
            return 1;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        foreach (parent::compareDataProvider() as $key => $data) {
            $data[2] = $data[2] == 0 ? 1 : $data[2];
            yield $key => $data;
        }
    }
}
