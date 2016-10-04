<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable\Tests\Units;

use Cubiche\Core\Enumerable\Enumerable;

/**
 * Sorted Enumerable Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SortedEnumerableTests extends EnumerableDecoratorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array($this->defaultTestedInstanceEnumerableTarget(), $this->defaultSortedComparator());
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTestedInstanceIterator()
    {
        return $this->sortIterator(
            $this->defaultTestedInstanceEnumerableTarget()->getIterator(),
            $this->defaultSortedComparator()
        );
    }
}
