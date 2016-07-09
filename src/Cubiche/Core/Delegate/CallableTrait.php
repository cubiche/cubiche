<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Delegate;

/**
 * Callable trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait CallableTrait
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->invokeWith(\func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function invokeWith(array $args)
    {
        return \call_user_func_array($this->innerCallable(), $args);
    }

    /**
     * @return callable
     */
    abstract protected function innerCallable();
}
