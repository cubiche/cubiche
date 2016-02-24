<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Comparable;

use Cubiche\Domain\Delegate\Delegate;

/**
 * Custom Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Custom extends Comparator
{
    /**
     * @var Delegate
     */
    protected $delegate;

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        $this->delegate = new Delegate($callable);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Comparator::compare()
     */
    public function compare($a, $b)
    {
        return $this->delegate->__invoke($a, $b);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\Comparator::accept()
     */
    public function accept(ComparatorVisitorInterface $visitor)
    {
        return $visitor->visitCustomComparator($this);
    }
}
