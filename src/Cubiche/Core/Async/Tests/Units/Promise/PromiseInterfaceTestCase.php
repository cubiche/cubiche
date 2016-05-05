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
use Cubiche\Core\Async\Promise\Deferred;
use Cubiche\Core\Async\Promise\RejectedPromise;

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
                    ->once()
        ;

        $this->chainingTest($promise);
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function chainingTest(PromiseInterface $promise)
    {
        $this
            ->given(
                $deferred = new Deferred(),
                $onFulfilled = $this->delegateMockWithReturn($deferred->promise())
            )
            ->when($thenPromise = $promise->then($onFulfilled))
            ->then()
                ->boolean($thenPromise->state()->equals(State::PENDING()))
                    ->isTrue()
        ;

        $this
            ->given(
                $onFulfilledThen = $this->delegateMock(),
                $onNotify = $this->delegateMock()
            )
            ->when(function () use ($deferred, $thenPromise, $onFulfilledThen, $onNotify) {
                $thenPromise->then($onFulfilledThen, null, $onNotify);
                $deferred->notify('state');
                $deferred->resolve('bar');
            })
            ->then()
                ->delegateCall($onNotify)
                    ->withArguments('state')
                    ->once()
                ->delegateCall($onFulfilledThen)
                    ->withArguments('bar')
                    ->once()
        ;

        $this
            ->given(
                $onFulfilled = $this->delegateMockWithReturn(new RejectedPromise($this->defaultRejectReason())),
                $onRejectedThen = $this->delegateMock()
            )
            ->when($promise->then($onFulfilled)->then(null, $onRejectedThen))
            ->then()
                ->delegateCall($onRejectedThen)
                    ->withArguments($this->defaultRejectReason())
                    ->once()
        ;
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
     * Test done.
     *
     * @param PromiseInterface $promise
     *
     * @dataProvider promiseDataProvider
     */
    public function testDone(PromiseInterface $promise)
    {
        $this
            ->given($promise)
            ->when($null = $promise->done())
            ->then()
                ->variable($null)
                    ->isNull();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this
                ->given(
                    $onFulfilled = $this->delegateMock(),
                    $onRejected = $this->delegateMock(),
                    $onNotify = $this->delegateMock(),
                    $value = $this->defaultResolveValue()
                )
                ->when($promise->done($onFulfilled, $onRejected, $onNotify))
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
                        $onFulfilled = $this->delegateMockWithException(new \Exception())
                    )
                    ->exception(function () use ($promise, $onFulfilled) {
                        $promise->done($onFulfilled);
                    })->isInstanceof(\Exception::class)
                ;
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this
                ->given(
                    $onFulfilled = $this->delegateMock(),
                    $onRejected = $this->delegateMock(),
                    $onNotify = $this->delegateMock(),
                    $reason = $this->defaultRejectReason()
                )
                ->when($promise->done($onFulfilled, $onRejected, $onNotify))
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
                    $onRejected = $this->delegateMockWithException(new \Exception())
                )
                ->exception(function () use ($promise, $onRejected) {
                    $promise->done(null, $onRejected);
                })->isInstanceof(\Exception::class)
            ;
        }
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
                $onRejected = $this->delegateMock()
            )
            ->when($otherwise = $promise->otherwise($onRejected))
            ->then()
                ->object($otherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals($otherwise->state()))
                    ->isTrue();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this
                ->then()
                    ->delegateCall($onRejected)
                        ->never()
            ;
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this
                ->given($reason = $this->defaultRejectReason())
                ->then()
                    ->delegateCall($onRejected)
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
                $onFulfilledOrRejected = $this->delegateMock(),
                $onNotify = $this->delegateMock()
            )
            ->when($always = $promise->always($onFulfilledOrRejected, $onNotify))
            ->then()
                ->object($always)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals($always->state()))
                    ->isTrue();

        if ($promise->state()->equals(State::FULFILLED())) {
            $this
                ->given($value = $this->defaultResolveValue())
                ->then()
                    ->delegateCall($onFulfilledOrRejected)
                        ->withArguments($value)
                        ->once()
            ;
        }

        if ($promise->state()->equals(State::REJECTED())) {
            $this
                ->given($reason = $this->defaultRejectReason())
                ->then()
                    ->delegateCall($onFulfilledOrRejected)
                        ->withArguments($reason)
                        ->once()
            ;
        }
    }

    /**
     * @return array
     */
    abstract protected function promiseDataProvider();
}
