<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Cubiche\Core\Delegate\Delegate;

/**
 * Callback Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Callback extends Selector
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
    public function apply($value)
    {
        return $this->callbackDelegate->__invoke($value);
    }

    /**
     * @return callable
     */
    public function target()
    {
        return $this->callbackDelegate->target();
    }
}
