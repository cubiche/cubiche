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

/**
 * Cacheable Enumerable Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CacheableEnumerableTests extends EnumerableDecoratorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array($this->defaultTestedInstanceEnumerableTarget());
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTestedInstanceIterator()
    {
        return new \IteratorIterator($this->defaultTestedInstanceEnumerableTarget());
    }
}
