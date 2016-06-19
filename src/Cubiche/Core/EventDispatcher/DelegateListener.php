<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventDispatcher;

use Cubiche\Core\Delegate\Delegate;

/**
 * DelegateListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DelegateListener extends Delegate
{
    /**
     * @var mixed
     */
    protected $callback;

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        parent::__construct($callable);

        $this->callback = $callable;
    }

    /**
     * @return mixed
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $listener
     *
     * @return bool
     */
    public function equals($listener)
    {
        if ($listener instanceof self) {
            $listener = $listener->callback();
        }

        return $this->callback == $listener;
    }
}
