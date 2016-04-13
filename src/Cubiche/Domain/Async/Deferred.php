<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Async;

use Cubiche\Core\Delegate\Delegate;

/**
 * Deferred.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Deferred implements DeferredInterface
{
    /**
     * @var PromiseInterface
     */
    protected $promise;

    /**
     * @var Delegate
     */
    protected $resolveDelegate;

    /**
     * @var Delegate
     */
    protected $rejectDelegate;

    /**
     * @var Delegate
     */
    protected $notifyDelegate;

    /**
     * @var Delegate
     */
    protected $cancelDelegate;

    /**
     * @return \Cubiche\Domain\Async\Deferred
     */
    public static function defer()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->promise = null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\DeferredInterface::promise()
     */
    public function promise()
    {
        if ($this->promise === null) {
            $this->promise = new Promise(
                new Delegate(function (Delegate $delegate) {
                    $this->resolveDelegate = $delegate;
                }),
                new Delegate(function (Delegate $delegate) {
                    $this->rejectDelegate = $delegate;
                }),
                new Delegate(function (Delegate $delegate) {
                    $this->notifyDelegate = $delegate;
                }),
                new Delegate(function (Delegate $delegate) {
                    $this->cancelDelegate = $delegate;
                })
            );
        }

        return $this->promise;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\DeferredInterface::resolve()
     */
    public function resolve($value = null)
    {
        $this->promise();

        $this->resolveDelegate->__invoke($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\DeferredInterface::reject()
     */
    public function reject($reason = null)
    {
        $this->promise();

        $this->rejectDelegate->__invoke($reason);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\DeferredInterface::notify()
     */
    public function notify($state = null)
    {
        $this->promise();

        $this->notifyDelegate->__invoke($state);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\DeferredInterface::cancel()
     */
    public function cancel()
    {
        $this->promise();

        return $this->cancelDelegate->__invoke();
    }
}
