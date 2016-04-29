<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Tests\Units\Promise;

use Cubiche\Core\Async\Loop\Loop;
use Cubiche\Core\Async\Promise\Promise;
use Cubiche\Core\Async\Promise\PromiseInterface;
use Cubiche\Core\Async\Promise\Promises;
use Cubiche\Core\Async\Promise\State;
use Cubiche\Core\Async\Tests\Units\TestCase;
use Cubiche\Core\Async\Promise\DeferredInterface;
use Cubiche\Core\Async\Promise\FulfilledPromise;
use Cubiche\Core\Async\Promise\RejectedPromise;

/**
 * Promises Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromisesTests extends TestCase
{
    /**
     * Test defer.
     */
    public function testDefer()
    {
        $this
            ->when($deferred = Promises::defer())
            ->then()
                ->object($deferred)
                    ->isInstanceOf(DeferredInterface::class)
                ->boolean($deferred->promise()->state()->equals(State::PENDING()))
                    ->isTrue()
            ;
    }

    /**
     * Test fulfilled.
     */
    public function testFulfilled()
    {
        $this
            ->when($fulfilled = Promises::fulfilled('foo'))
            ->then()
                ->object($fulfilled)
                    ->isInstanceOf(FulfilledPromise::class)
        ;
    }

    /**
     * Test rejected.
     */
    public function testRejected()
    {
        $this
            ->when($rejected = Promises::rejected())
            ->then()
                ->object($rejected)
                    ->isInstanceOf(RejectedPromise::class)
        ;
    }

    /**
     * Test all.
     */
    public function testAll()
    {
        $this
            ->given(
                $deferred = Promises::defer(),
                $promise1 = Promises::fulfilled(1),
                $promise2 = Promises::fulfilled(2)
            )
            ->when($all = Promises::all(array($deferred->promise(), $promise1, $promise2)))
            ->then()
                ->object($all)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($all->state()->equals(State::PENDING()))
                    ->isTrue()
        ;

        $this
            ->when($deferred->resolve(0))
            ->then()
                ->boolean($all->state()->equals(State::FULFILLED()))
                  ->isTrue()
        ;

        $this
            ->given($onFulfilled = $this->delegateMock())
            ->when($all->then($onFulfilled))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments(array(0, 1, 2))
                    ->once()
        ;
    }

    /**
     * Test map.
     */
    public function testMap()
    {
        $this
            ->given(
                $promises = array(Promises::fulfilled(0), Promises::fulfilled(1), Promises::fulfilled(2)),
                $onFulfilled = $this->delegateMock()
            )
            ->when(Promises::map($promises, function ($value) {
                return $value + 1;
            })->then($onFulfilled))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments(array(1, 2, 3))
                    ->once()
        ;

        $this
            ->given($onFulfilled = $this->delegateMock())
            ->when(Promises::map(array())->then($onFulfilled))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments(array())
                    ->once()
        ;

        $this
            ->given(
                $reason = new \Exception(),
                $onRejected = $this->delegateMock()
            )
            ->when(Promises::map(array(Promises::rejected($reason)))->otherwise($onRejected))
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
        ;
    }

    /**
     * Test timeout.
     */
    public function testTimeout()
    {
        $this
            ->given(
                $loop = new Loop(),
                $deferred = Promises::defer()
            )
            ->when($timeout = Promises::timeout($deferred->promise(), 0.01, $loop))
            ->then()
                ->object($timeout)
                    ->isInstanceOf(PromiseInterface::class)
            ;

        $this
            ->given($onRejected = $this->delegateMock())
            ->when(function () use ($timeout, $onRejected, $loop) {
                $timeout->otherwise($onRejected);
                $loop->run();
            })
            ->then()
                ->delegateCall($onRejected)
                    ->once()
        ;

        $this
            ->given(
                $onFulfilled = $this->delegateMock(),
                $timeout = Promises::timeout(Promises::fulfilled('foo'), 0.01, $loop)
            )
            ->when(function () use ($timeout, $onFulfilled, $loop) {
                $timeout->then($onFulfilled);
                $loop->run();
            })
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments('foo')
                    ->once()
        ;

        $this
            ->given(
                $reason = new \Exception(),
                $onRejected = $this->delegateMock(),
                $timeout = Promises::timeout(Promises::rejected($reason), 0.01, $loop)
            )
            ->when(function () use ($timeout, $onRejected, $loop) {
                $timeout->otherwise($onRejected);
                $loop->run();
            })
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
            ;
    }

    /**
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given($loop = new Loop())
            ->when($value = Promises::get(Promises::fulfilled('foo'), $loop))
            ->then()
                ->variable($value)
                    ->isEqualTo('foo')
        ;

        $this
            ->given($deferred = Promises::defer())
            ->let($value = null)
            ->when(function () use ($loop, $deferred, &$value) {
                $loop->enqueue(function () use ($deferred) {
                    $deferred->resolve('bar');
                });
                $value = Promises::get($deferred->promise(), $loop);
            })
            ->then()
                ->variable($value)
                    ->isEqualTo('bar')
        ;

        $this
            ->given($deferred = Promises::defer())
            ->exception(function () use ($loop, $deferred) {
                Promises::get($deferred->promise(), $loop, 0.01);
            })
        ;
    }
}
