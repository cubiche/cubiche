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
 * Rejected Promise class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class RejectedPromise extends AbstractPromise
{
    /**
     * @var mixed
     */
    protected $reason;

    /**
     * @param mixed $reason
     */
    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onNotify = null)
    {
        if ($onRejected === null) {
            return $this;
        }

        return new self($this->rejectActual($onRejected));
    }

    /**
     * {@inheritdoc}
     */
    public function state()
    {
        return State::REJECTED();
    }

    /**
     * @param callable $onRejected
     *
     * @return mixed
     */
    private function rejectActual(callable $onRejected)
    {
        try {
            $onRejected($this->reason);
        } catch (\Exception $e) {
            return $e;
        }

        return $this->reason;
    }
}
