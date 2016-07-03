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
 * ReverseComparatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ReverseComparatorTests extends AbstractComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        $comparator = new Comparator();

        return array($comparator->reverse());
    }
}
