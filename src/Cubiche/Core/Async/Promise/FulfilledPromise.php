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
 * Fulfilled Promise class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FulfilledPromise extends AbstractPromise
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        if ($value instanceof PromiseInterface) {
            throw new \InvalidArgumentException(\sprintf('You cannot create %s with a promise.', self::class));
        }
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($onFulfilled === null) {
            return $this;
        }

        try {
            return $this->resolveActual($onFulfilled);
        } catch (\Exception $e) {
            return new RejectedPromise($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($onFulfilled !== null) {
            $onFulfilled($this->value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function state()
    {
        return State::FULFILLED();
    }

    /**
     * @param callable $onFulfilled
     *
     * @return mixed
     */
    private function resolveActual(callable $onFulfilled)
    {
        $value = $onFulfilled($this->value);

        return $value instanceof PromiseInterface ? $value : new self($value);
    }
}
