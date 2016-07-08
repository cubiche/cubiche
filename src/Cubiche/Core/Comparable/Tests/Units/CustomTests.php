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

/**
 * Custom Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CustomTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(function () {
            return -1;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        return array(
            array(1, 2, -1),
            array(1, 3, -1),
            array(4, 1, -1),
        );
    }
}
