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

use Cubiche\Core\Async\Promise\PromiseInterface;
use Cubiche\Core\Async\Promise\State;
use Cubiche\Core\Async\Tests\Units\TestCase;

/**
 * Promise Interface Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PromiseInterfaceTestCase extends TestCase
{
    protected $defaultRejectReason;

    /**
     * @return mixed
     */
    protected function defaultResolveValue()
    {
        return 'foo';
    }

    /**
     * @return mixed
     */
    protected function defaultRejectReason()
    {
        if ($this->defaultRejectReason === null) {
            $this->defaultRejectReason = new \Exception();
        }

        return $this->defaultRejectReason;
    }

    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(PromiseInterface::class)
        ;
    }

    /**
     * Test then.
     *
     * @param PromiseInterface $promise
     *
     * @dataProvider promiseDataProvider
     */
    public function testThen(PromiseInterface $promise)
    {
        $this
            ->given($promise)
            ->when($then = $promise->then())
            ->then()
                ->object($then)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals($then->state()))
                    ->isTrue();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this->fulfilledThenTest($promise);
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this->rejectedThenTest($promise);
        }

        if ($promise->state()->equals(State::PENDING())) {
            $this->pendingThenTest($promise);
        }
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function fulfilledThenTest(PromiseInterface $promise)
    {
        $this
            ->given(
                $onFulfilled = $this->delegateMock(),
                $onRejected = $this->delegateMock(),
                $onNotify = $this->delegateMock(),
                $value = $this->defaultResolveValue()
            )
            ->when($promise->then($onFulfilled, $onRejected, $onNotify))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments($value)
                    ->once()
                ->delegateCall($onRejected)
                    ->never()
                ->delegateCall($onNotify)
                    ->never();

        $this
            ->given(
                $onFulfilled = $this->delegateMockWithReturn('bar'),
                $onFulfilledThen = $this->delegateMock()
            )
            ->when($promise->then($onFulfilled)->then($onFulfilledThen))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments($value)
                    ->once()
                ->delegateCall($onFulfilledThen)
                    ->withArguments('bar')
                    ->once();

        $this
            ->given(
                $e = new \Exception(),
                $onFulfilled = $this->delegateMockWithException($e),
                $onRejectedThen = $this->delegateMock(),
                $onFulfilledThen = $this->delegateMock()
            )
            ->when($promise->then($onFulfilled)->then($onFulfilledThen, $onRejectedThen))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments($value)
                    ->once()
                ->delegateCall($onFulfilledThen)
                    ->never()
                ->delegateCall($onRejectedThen)
                    ->withArguments($e)
                    ->once();
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function rejectedThenTest(PromiseInterface $promise)
    {
        $this
            ->given(
                $onFulfilled = $this->delegateMock(),
                $onRejected = $this->delegateMock(),
                $onNotify = $this->delegateMock(),
                $reason = $this->defaultRejectReason()
            )
            ->when($promise->then($onFulfilled, $onRejected, $onNotify))
            ->then()
                ->delegateCall($onFulfilled)
                    ->never()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($onNotify)
                    ->never()
        ;

        $this
            ->given(
                $e = new \Exception(),
                $onRejected = $this->delegateMockWithException($e),
                $onRejectedThen = $this->delegateMock(),
                $onFulfilledThen = $this->delegateMock()
            )
            ->when($promise->then(null, $onRejected)->then($onFulfilledThen, $onRejectedThen))
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($onFulfilledThen)
                    ->never()
                ->delegateCall($onRejectedThen)
                     ->withArguments($e)
                     ->once();
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function pendingThenTest(PromiseInterface $promise)
    {
        $this
            ->given(
                $onFulfilled = $this->delegateMock(),
                $onRejected = $this->delegateMock(),
                $onNotify = $this->delegateMock()
            )
            ->when($promise->then($onFulfilled, $onRejected, $onNotify))
            ->then()
                ->delegateCall($onFulfilled)
                    ->never()
                ->delegateCall($onRejected)
                    ->never()
                ->delegateCall($onNotify)
                    ->never();
    }

    /**
     * Test otherwise.
     *
     * @param PromiseInterface $promise
     *
     * @dataProvider promiseDataProvider
     */
    public function testOtherwise(PromiseInterface $promise)
    {
        $this
            ->given(
                $promise,
                $catch = $this->delegateMock()
            )
            ->when($otherwise = $promise->otherwise($catch))
            ->then()
                ->object($otherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals($otherwise->state()))
                    ->isTrue();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this
                ->then()
                    ->delegateCall($catch)
                        ->never()
            ;
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this
                ->given($reason = $this->defaultRejectReason())
                ->then()
                    ->delegateCall($catch)
                        ->withArguments($reason)
                        ->once()
            ;
        }
    }

    /**
     * Test always.
     *
     * @param PromiseInterface $promise
     *
     * @dataProvider promiseDataProvider
     */
    public function testAlways(PromiseInterface $promise)
    {
        $this
            ->given(
                $promise,
                $finally = $this->delegateMock(),
                $onNotify = $this->delegateMock()
            )
            ->when($always = $promise->always($finally, $onNotify))
            ->then()
                ->object($always)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals($always->state()))
                    ->isTrue();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this
                ->given($value = $this->defaultResolveValue())
                ->then()
                    ->delegateCall($finally)
                        ->withArguments($value, null)
                        ->once()
            ;
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this
                ->given($reason = $this->defaultRejectReason())
                ->then()
                    ->delegateCall($finally)
                        ->withArguments(null, $reason)
                        ->once()
            ;
        }
    }

    /**
     * @return array
     */
    abstract protected function promiseDataProvider();
}
