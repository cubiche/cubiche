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
 * Set Operations trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait Set
{
    /**
     * @param callable $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function distinct(callable $equalityComparer = null)
    {
        return new DistinctEnumerable($this, $equalityComparer);
    }

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function except($enumerable, callable $equalityComparer = null)
    {
        $set = Enumerable::from($enumerable)->distinct($equalityComparer);

        return Enumerable::from($this)
            ->where(function ($value) use ($set, $equalityComparer) {
                return !$set->contains($value, $equalityComparer);
            })
            ->distinct($equalityComparer);
    }

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function intersect($enumerable, callable $equalityComparer = null)
    {
        $set = Enumerable::from($enumerable)->distinct($equalityComparer);

        return Enumerable::from($this)
            ->where(function ($value) use ($set, $equalityComparer) {
                return $set->contains($value, $equalityComparer);
            })
            ->distinct($equalityComparer);
    }

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $equalityComparer
     *
     * @return \Cubiche\Core\Enumerable\EnumerableInterface
     */
    public function union($enumerable, callable $equalityComparer = null)
    {
        $iterator = new \AppendIterator();
        $iterator->append(Enumerable::from($this)->getIterator());
        $iterator->append(Enumerable::from($enumerable)->getIterator());

        return Enumerable::from($iterator)->distinct($equalityComparer);
    }
}
