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
 * Observable Resolver class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ObservableResolver extends Resolver
{
    /**
     * @var callable
     */
    protected $resolveCallback;

    /**
     * @var callable
     */
    protected $rejectCallback;

    /**
     * @var callable
     */
    protected $notifyCallback;

    /**
     * @param callable $resolveCallback
     * @param callable $rejectCallback
     * @param callable $notifyCallback
     */
    public function __construct(
        callable $resolveCallback = null,
        callable $rejectCallback = null,
        callable $notifyCallback = null
    ) {
        $this->resolveCallback = $resolveCallback;
        $this->rejectCallback = $rejectCallback;
        $this->notifyCallback = $notifyCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        $this->callRejectCallback($reason);
    }

    /**
     * {@inheritdoc}
     */
    protected function onResolve($value = null)
    {
        $this->callResolveCallback($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function onNotify($state = null)
    {
        $this->callNotifyCallback($state);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function callResolveCallback($value = null)
    {
        return $this->invokeCallback($this->resolveCallback, $value);
    }

    /**
     * @param mixed $reason
     *                      return mixed
     */
    protected function callRejectCallback($reason = null)
    {
        return $this->invokeCallback($this->rejectCallback, $reason);
    }

    /**
     * @param mixed $state
     *
     * @return mixed
     */
    protected function callNotifyCallback($state = null)
    {
        return $this->invokeCallback($this->notifyCallback, $state);
    }

    /**
     * @param callable $callback
     * @param mixed    $argument
     *
     * @return mixed
     */
    private function invokeCallback(callable $callback = null, $argument = null)
    {
        return $callback === null ? $argument : \call_user_func($callback, $argument);
    }
}
