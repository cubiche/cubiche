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

use Cubiche\Core\Delegate\Delegate;

/**
 * Resolver Proxy class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ResolverProxy implements ResolverInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @var Delegate
     */
    protected $onFulfilled;

    /**
     * @var Delegate
     */
    protected $onRejected;

    /**
     * @var Delegate
     */
    protected $onNotify;

    /**
     * @param ResolverInterface $resolver
     * @param callable          $onFulfilled
     * @param callable          $onRejected
     * @param callable          $onNotify
     */
    public function __construct(
        ResolverInterface $resolver,
        callable $onFulfilled = null,
        callable $onRejected = null,
        callable $onNotify = null
    ) {
        $this->resolver = $resolver;
        if ($onFulfilled !== null) {
            $this->onFulfilled = new Delegate($onFulfilled);
        }
        if ($onRejected !== null) {
            $this->onRejected = new Delegate($onRejected);
        }
        if ($onNotify !== null) {
            $this->onNotify = new Delegate($onNotify);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($value = null)
    {
        try {
            if ($this->onFulfilled !== null) {
                $value = $this->onFulfilled->__invoke($value);
            }
            if ($value instanceof PromiseInterface) {
                $value->then(function ($actual) {
                    $this->resolver->resolve($actual);
                }, function ($reason) {
                    $this->resolver->reject($reason);
                }, function ($state) {
                    $this->resolver->notify($state);
                });
            } else {
                $this->resolver->resolve($value);
            }
        } catch (\Throwable $e) {
            $this->reject($e);
        } catch (\Exception $e) {
            $this->reject($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reject($reason = null)
    {
        try {
            if ($this->onRejected !== null) {
                $this->onRejected->__invoke($reason);
            }
        } catch (\Throwable $e) {
            $reason = $e;
        } catch (\Exception $e) {
            $reason = $e;
        }

        $this->resolver->reject($reason);
    }

    /**
     * {@inheritdoc}
     */
    public function notify($state = null)
    {
        try {
            if ($this->onNotify !== null) {
                $this->onNotify->__invoke($state);
            }
        } catch (\Throwable $e) {
            $this->reject($e);
        } catch (\Exception $e) {
            $this->reject($e);
        }
    }
}
