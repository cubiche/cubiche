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
use Cubiche\Core\Enumerable\Tests\Fixtures\Value;

/**
 * Filtered Enumerable Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FilteredEnumerableTests extends EnumerableDecoratorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array($this->defaultTestedInstanceEnumerableTarget(), $this->defaultTestedInstancePredicate());
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTestedInstanceIterator()
    {
        return new \CallbackFilterIterator(
            $this->defaultTestedInstanceEnumerableTarget()->getIterator(),
            $this->defaultTestedInstancePredicate()
        );
    }

    /**
     * @return \Closure
     */
    protected function defaultTestedInstancePredicate()
    {
        return function (Value $value) {
            return $value->value() > 2;
        };
    }
}
