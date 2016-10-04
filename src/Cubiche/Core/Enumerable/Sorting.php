<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

/**
 * Sorting Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Sorting
{
    /**
     * @param callable $comparator
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function sorted(callable $comparator = null)
    {
        return new SortedEnumerable($this, $comparator);
    }
}
