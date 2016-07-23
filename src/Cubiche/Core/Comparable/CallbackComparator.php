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
 * Callback Comparator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CallbackComparator extends Comparator
{
    /**
     * @var Delegate
     */
    protected $callbackDelegate;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callbackDelegate = new Delegate($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return $this->callbackDelegate->__invoke($a, $b);
    }

    /**
     * @return callable
     */
    public function target()
    {
        return $this->callbackDelegate->target();
    }
}
