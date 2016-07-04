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
 * Abstract Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractEnumerable implements EnumerableInterface
{
    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \iterator_count($this->getIterator());
    }

    /**
     * {@inheritdoc}
     */
    public function where(callable $predicate)
    {
        return new FilteredEnumerable($this, $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function sorted(callable $comparator)
    {
        return new SortedEnumerable($this, $comparator);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return new SlicedEnumerable($this, $offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return \iterator_to_array($this->getIterator(), true);
    }
}
