<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

use Cubiche\Core\Delegate\AbstractCallable;
use Cubiche\Core\Visitor\VisiteeTrait;

/**
 * Comparator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Comparator extends AbstractCallable implements ComparatorInterface
{
    use VisiteeTrait;

    /**
     * @param callable $comparator
     *
     * @return \Cubiche\Core\Comparable\ComparatorInterface
     */
    public static function from(callable $comparator)
    {
        if ($comparator instanceof ComparatorInterface) {
            return $comparator;
        }

        return new Custom($comparator);
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        if ($a instanceof ComparableInterface) {
            return $a->compareTo($b);
        }

        if ($b instanceof ComparableInterface) {
            return -1 * $b->compareTo($a);
        }

        return $a < $b ? -1 : ($a == $b ? 0 : 1);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new ReverseComparator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function otherwise(callable $comparator)
    {
        return new MultiComparator($this, $comparator);
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallable()
    {
        return array($this, 'compare');
    }
}
