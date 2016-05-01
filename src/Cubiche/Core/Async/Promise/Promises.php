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

use Cubiche\Core\Async\Loop\LoopInterface;

/**
 * Promises class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Promises
{
    /**
     * @return \Cubiche\Core\Async\Promise\DeferredInterface
     */
    public static function defer()
    {
        return new Deferred();
    }

    /**
     * @param mixed $value
     *
     * @return \Cubiche\Core\Async\Promise\PromiseInterface
     */
    public static function fulfilled($value = null)
    {
        return new FulfilledPromise($value);
    }

    /**
     * @param mixed $reason
     *
     * @return \Cubiche\Core\Async\Promise\PromiseInterface
     */
    public static function rejected($reason = null)
    {
        return new RejectedPromise($reason);
    }

    /**
     * @param PromiseInterface[] $promises
     *
     * @return \Cubiche\Core\Async\Promise\PromiseInterface
     */
    public static function all($promises)
    {
        return self::map($promises);
    }

    /**
     * @param PromiseInterface[] $promises
     * @param callable           $map
     *
     * @return \Cubiche\Core\Async\Promise\PromiseInterface
     */
    public static function map($promises, callable $map = null)
    {
        $pending = \count($promises);
        if ($pending == 0) {
            return self::fulfilled(array());
        }

        $deferred = self::defer();
        $results = array();

        /*
         * @var \Cubiche\Core\Async\Promise\PromiseInterface
         */
        foreach ($promises as $key => $promise) {
            $promise->then(
                function ($value) use ($deferred, &$results, $map, $key, &$pending) {
                    if ($map !== null) {
                        $value = $map($value);
                    }
                    $results[$key] = $value;

                    if (--$pending == 0) {
                        $deferred->resolve($results);
                    }
                },
                function ($reason) use ($deferred) {
                    $deferred->reject($reason);
                }
            );
        }

        return $deferred->promise();
    }

    /**
     * @param PromiseInterface $promise
     * @param int|float        $time
     * @param LoopInterface    $loop
     *
     * @return \Cubiche\Core\Async\Promise\PromiseInterface
     */
    public static function timeout(PromiseInterface $promise, $time, LoopInterface $loop)
    {
        $deferred = self::defer();
        $timer = $loop->timeout(
            function () use ($promise, $deferred, $time) {
                $deferred->reject(new TimeoutException($time));
            },
            $time
        );

        $promise->then(
            function ($value = null) use ($deferred, $timer) {
                $timer->cancel();
                $deferred->resolve($value);
            },
            function ($reason = null) use ($deferred, $timer) {
                $timer->cancel();
                $deferred->reject($reason);
            }
        );

        return $deferred->promise();
    }

    /**
     * @param PromiseInterface $promise
     * @param LoopInterface    $loop
     * @param int|float        $timeout
     *
     * @throws RejectionException
     *
     * @return mixed
     */
    public static function get(PromiseInterface $promise, LoopInterface $loop, $timeout = null)
    {
        $result = null;
        $rejectionReason = null;
        if ($timeout !== null) {
            $promise = self::timeout($promise, $timeout, $loop);
        }

        $promise->then(
            function ($value = null) use (&$result) {
                $result = $value;
            },
            function ($reason = null) use (&$rejectionReason) {
                $rejectionReason = $reason;
            }
        )->always(
            function () use ($loop) {
                $loop->stop();
            }
        );

        while ($promise->state()->equals(State::PENDING())) {
            $loop->run();
        }

        if ($promise->state()->equals(State::FULFILLED())) {
            return $result;
        }

        throw new RejectionException($rejectionReason);
    }

    /**
     * @param ThenableInterface $thenable
     *
     * @return \Cubiche\Core\Async\Promise\PromisorInterface
     */
    public static function promisor(ThenableInterface $thenable)
    {
        return new ThenablePromisor($thenable);
    }
}
