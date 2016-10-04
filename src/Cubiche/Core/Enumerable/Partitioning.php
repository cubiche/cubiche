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
 * Partitioning Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Partitioning
{
    /**
     * @param int $offset
     * @param int $length
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function slice($offset, $length = null)
    {
        return new SlicedEnumerable(Enumerable::from($this), $offset, $length);
    }

    /**
     * @param int $count
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function skip($count)
    {
        return $this->slice($count);
    }

    /**
     * @param int $length
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function limit($length)
    {
        return $this->slice(0, $length);
    }
}
