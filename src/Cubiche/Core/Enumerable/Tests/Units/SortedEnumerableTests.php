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
        return array($this->defaultTestedInstanceEnumerableTarget(), $this->defaultSortedCompartor());
    }

    /**
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    protected function defaultTestedInstanceEnumerableTarget()
    {
        return Enumerable::from(array(4, 1, 6, 2, 3, 5));
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTestedInstanceIterator()
    {
        return $this->sortIterator(
            $this->defaultTestedInstanceEnumerableTarget()->getIterator(),
            $this->defaultSortedCompartor()
        );
    }
}
