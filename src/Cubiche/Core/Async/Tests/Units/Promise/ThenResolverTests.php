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

use Cubiche\Core\Async\Promise\Deferred;
use Cubiche\Core\Async\Promise\RejectedPromise;

/**
 * Then Resolver Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ThenResolverTests extends ObservableResolverTests
{
    /**
     * {@inheritdoc}
     */
    public function testResolve()
    {
        parent::testResolve();

        $this
            ->given(
                $deferred = new Deferred(),
                $onFulfilled = $this->delegateMockWithReturn($deferred->promise()),
                /** @var \Cubiche\Core\Async\Promise\ThenResolver $resolver */
                $resolver = $this->newTestedInstance($onFulfilled),
                $onFulfilledPromise = $this->delegateMock(),
                $onNotifyPromise = $this->delegateMock()
            )
            ->when(function () use ($resolver, $onFulfilledPromise, $onNotifyPromise) {
                $resolver->resolve();
                $resolver->promise()->then($onFulfilledPromise, null, $onNotifyPromise);
            })
            ->then()
                ->delegateCall($onFulfilledPromise)
                    ->never()
                ->delegateCall($onNotifyPromise)
                    ->never()
        ;

        $this
            ->when($deferred->notify('state'))
            ->then()
                ->delegateCall($onNotifyPromise)
                    ->withArguments('state')
                    ->once()
        ;

        $this
            ->when($deferred->resolve('foo'))
            ->then()
                ->delegateCall($onFulfilledPromise)
                    ->withArguments('foo')
                    ->once()
        ;

        $this
            ->given(
                $reason = new \Exception(),
                $onFulfilled = $this->delegateMockWithReturn(new RejectedPromise($reason)),
                /** @var \Cubiche\Core\Async\Promise\ThenResolver $resolver */
                $resolver = $this->newTestedInstance($onFulfilled),
                $onRejectedPromise = $this->delegateMock()
            )
            ->when(function () use ($resolver, $onRejectedPromise) {
                $resolver->resolve();
                $resolver->promise()->then(null, $onRejectedPromise);
            })
            ->then()
                ->delegateCall($onRejectedPromise)
                    ->withArguments($reason)
                    ->once()
        ;
    }

    /**
     * Test reject.
     */
    public function testReject()
    {
        parent::testReject();

        $this
            ->given(
                $reason = new \Exception(),
                $onRejected = $this->delegateMockWithException($reason),
                /** @var \Cubiche\Core\Async\Promise\ThenResolver $resolver */
                $resolver = $this->newTestedInstance(null, $onRejected),
                $onRejectedPromise = $this->delegateMock()
            )
            ->when(function () use ($resolver, $onRejectedPromise) {
                $resolver->reject();
                $resolver->promise()->then(null, $onRejectedPromise);
            })
            ->then()
                ->delegateCall($onRejectedPromise)
                    ->withArguments($reason)
                    ->once();
    }
}
