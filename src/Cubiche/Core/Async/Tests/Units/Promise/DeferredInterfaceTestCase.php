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
use Cubiche\Core\Async\Promise\DeferredInterface;
use Cubiche\Core\Async\Promise\State;

/**
 * Deferred Interface Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DeferredInterfaceTestCase extends PromisorInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(DeferredInterface::class)
        ;
    }

    /**
     * Test __constructor.
     */
    public function testCreate()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance()
            )
            ->then()
                ->boolean($deferred->promise()->state()->equals(State::PENDING()))
                    ->isTrue()
        ;
    }

    /**
     * Test resolve.
     */
    public function testResolve()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance(),
                $value = 'foo'
            )
            ->when($deferred->resolve($value))
            ->then()
                ->boolean($deferred->promise()->state()->equals(State::FULFILLED()))
                    ->isTrue()
        ;

        $this
            ->given($onFulfilled = $this->delegateMock())
            ->when($deferred->promise()->then($onFulfilled))
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments($value)
                    ->once()
        ;

        $this->invalidActionTest($deferred);
    }

    /**
     * Test reject.
     */
    public function testReject()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance(),
                $reason = new \Exception()
            )
            ->when($deferred->reject($reason))
            ->then()
                ->boolean($deferred->promise()->state()->equals(State::REJECTED()))
                    ->isTrue()
            ;

        $this
            ->given($onRejected = $this->delegateMock())
            ->when($deferred->promise()->then(null, $onRejected))
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                     ->once()
        ;

        $this->invalidActionTest($deferred);
    }

    /**
     * Test notify.
     */
    public function testNotify()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance(),
                $onNotify = $this->delegateMock()
            )
            ->when(function () use ($deferred, $onNotify) {
                $deferred->promise()->then(null, null, $onNotify);
                $deferred->notify('foo');
            })
            ->then()
                ->delegateCall($onNotify)
                    ->withArguments('foo')
                    ->once()
            ;
    }

    /**
     * @param DeferredInterface $deferred
     */
    protected function invalidActionTest(DeferredInterface $deferred)
    {
        $this
            ->given($deferred)
            ->exception(function () use ($deferred) {
                $deferred->resolve();
            })
                ->isInstanceOf(\LogicException::class)
            ->exception(function () use ($deferred) {
                $deferred->reject();
            })
                ->isInstanceOf(\LogicException::class)
            ->exception(function () use ($deferred) {
                $deferred->notify();
            })
                ->isInstanceOf(\LogicException::class)
        ;
    }
}
