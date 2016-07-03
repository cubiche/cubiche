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

use Cubiche\Core\Delegate\Delegate;

/**
 * Custom Comparator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Custom extends AbstractComparator
{
    /**
     * @var Delegate
     */
    protected $delegate;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->delegate = new Delegate($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return $this->delegate->__invoke($a, $b);
    }
}
