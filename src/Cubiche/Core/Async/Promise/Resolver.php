<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Promise;

/**
 * Resolver class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
        try {
            $this->onResolve($value);
        } catch (\Exception $e) {
            $this->onInnerFailure($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        try {
            $this->onNotify($state);
        } catch (\Exception $e) {
            $this->onInnerFailure($e);
        }
    }

    /**
     * @param mixed $value
     */
    abstract protected function onResolve($value = null);

    /**
     * @param mixed $state
     */
    abstract protected function onNotify($state = null);

    /**
     * @param \Exception $reason
     */
    protected function onInnerFailure(\Exception $reason)
    {
        $this->reject($reason);
    }
}
