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

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Comparator Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait ComparatorTrait
{
    /**
     * @param string $method
     * @param array  $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, array $arguments)
    {
        if ($method === 'or') {
            return call_user_func_array(array($this, 'orX'), $arguments);
        }

        throw new \BadMethodCallException(\sprintf('Call to undefined method %s::%s', \get_class($this), $method));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::compare()
     */
    public function compare($a, $b)
    {
        if ($a instanceof ComparableInterface) {
            return $a->compareTo($b);
        }

        return $a < $b ? -1 : ($a == $b ? 0 : 1);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::reverse()
     */
    public function reverse()
    {
        return new ReverseComparator($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Comparable\ComparatorInterface::orX()
     */
    public function orX(ComparatorInterface $other)
    {
        return new MultiComparator($this, $other);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Visitee::accept()
     */
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof ComparatorVisitorInterface) {
            return $this->acceptComparatorVisitor($visitor);
        }

        return parent::accept($visitor);
    }
}
