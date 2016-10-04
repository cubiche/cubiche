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
 * Abstract Enumerable class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractEnumerable implements EnumerableInterface
{
    use Filtering, Partitioning, Sorting, Quantifier, Set;

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
    public function toArray($associative = false)
    {
        return \iterator_to_array($this->getIterator(), $associative);
    }
}
