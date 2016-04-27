<?php

/**
 * This file is part of the Cubiche/Async component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units\Loop;

use Cubiche\Core\Async\Promise\PromiseInterface;
use Cubiche\Core\Async\Promise\State;
use mageekguy\atoum\mock\stream as Stream;
use React\EventLoop\LoopInterface as BaseLoopInterface;
use Cubiche\Core\Async\Loop\Timer\TimerInterface;

/**
 * Loop Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LoopTests extends LoopInterfaceTestCase
{
    /**
     * Test addReadStream method.
     */
    public function testAddReadStream()
    {
        $this->proxyMethodTest('addReadStream', array(Stream::get(), $this->delegateMock()));
    }

    /**
     * Test addWriteStream method.
     */
    public function testAddWriteStream()
    {
        $this->proxyMethodTest('addWriteStream', array(Stream::get(), $this->delegateMock()));
    }

    /**
     * Test removeReadStream method.
     */
    public function testRemoveReadStream()
    {
        $this->proxyMethodTest('removeReadStream', array(Stream::get()));
    }

    /**
     * Test removeWriteStream method.
     */
    public function testRemoveWriteStream()
    {
        $this->proxyMethodTest('removeWriteStream', array(Stream::get()));
    }

    /**
     * Test RemoveStream method.
     */
    public function testRemoveStream()
    {
        $this->proxyMethodTest('removeStream', array(Stream::get()));
    }

    /**
     * Test Timeout method.
     */
    public function testTimeout()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Loop\LoopInterface $loop */
                $loop = $this->newDefaultTestedInstance(),
                $task = $this->delegateMockWithReturn('foo')
            )
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
            ->when($timer = $loop->timeout($task, 0.001))
            ->then()
                ->object($timer)
                    ->isInstanceOf(TimerInterface::class)
                ->boolean($timer->state()->equals(State::PENDING()))
                    ->isTrue()
                ->float($timer->interval())
                    ->isEqualTo(0.001)
                ->integer($timer->iterations())
                    ->isEqualTo(0)
                ->integer($timer->maxIterations())
                    ->isEqualTo(1)
                ->boolean($timer->isActive())
                    ->isTrue()
        ;

        $this
            ->given($onFulfilled = $this->delegateMock())
            ->when(function () use ($loop, $timer, $onFulfilled) {
                $timer->then($onFulfilled);
                $loop->tick();
            })
            ->then()
                ->delegateCall($task)
                    ->once()
                ->delegateCall($onFulfilled)
                    ->withArguments('foo')
                    ->once()
        ;
    }

    /**
     * Test Timer method.
     */
    public function testTimer()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Loop\LoopInterface $loop */
                $loop = $this->newDefaultTestedInstance(),
                $task = $this->delegateMockWithReturn('foo')
            )
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
            ->when($timer = $loop->timer($task, 0.001, 2))
            ->then()
                ->object($timer)
                    ->isInstanceOf(TimerInterface::class)
                ->boolean($timer->state()->equals(State::PENDING()))
                    ->isTrue()
                ->float($timer->interval())
                    ->isEqualTo(0.001)
                ->integer($timer->iterations())
                    ->isEqualTo(0)
                ->integer($timer->maxIterations())
                    ->isEqualTo(2)
                ->boolean($timer->isActive())
                    ->isTrue()
        ;

        $this
        ->given(
            $onRejected = $this->delegateMock(),
            $onNotify = $this->delegateMock()
        )
        ->when(function () use ($loop, $timer, $onRejected, $onNotify) {
            $timer->then(null, $onRejected, $onNotify);
            $loop->run();
        })
        ->then()
            ->delegateCall($task)
                ->twice()
            ->delegateCall($onRejected)
                ->once()
            ->delegateCall($onNotify)
                ->withArguments('foo')
                ->twice()
        ;
    }

    /**
     * Test Next method.
     */
    public function testNext()
    {
        $this->scheduleTickTest('next');
    }

    /**
     * Test Enqueue method.
     */
    public function testEnqueue()
    {
        $this->scheduleTickTest('enqueue');
    }

    /**
     * Test Tick method.
     */
    public function testTick()
    {
        $this->proxyMethodTest('tick');
    }

    /**
     * Test Run method.
     */
    public function testRun()
    {
        $this->proxyMethodTest('run');
    }

    /**
     * Test Stop method.
     */
    public function testStop()
    {
        $this->proxyMethodTest('stop');
    }

    /**
     * @param string $method
     *
     * @return string
     */
    protected function scheduleTickTest($method)
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Loop\LoopInterface $loop */
                $loop = $this->newDefaultMockTestedInstance(),
                $task = $this->delegateMockWithReturn('foo')
            )
            /* @var \Cubiche\Core\Async\Promise\PromiseInterface $promise */
            ->when($promise = $loop->$method($task))
            ->then()
                ->object($promise)
                    ->isInstanceOf(PromiseInterface::class)
                ->boolean($promise->state()->equals(State::PENDING()))
                    ->isTrue()
        ;

        $this
            ->given($onFulfilled = $this->delegateMock())
            ->when(function () use ($loop, $promise, $onFulfilled) {
                $promise->then($onFulfilled);
                $loop->tick();
            })
            ->then()
                ->delegateCall($task)
                    ->once()
                ->delegateCall($onFulfilled)
                    ->withArguments('foo')
                    ->once()
        ;
    }

    /**
     * @param string $method
     * @param array  $arguments
     */
    protected function proxyMethodTest($method, array $arguments = array())
    {
        $methodCall = $this
            ->given(
                $baseLoopMock = $this->newMockInstance(BaseLoopInterface::class),
                /** @var \Cubiche\Core\Async\Loop\LoopInterface $loop */
                $loop = $this->newTestedInstance($baseLoopMock)
            )
            ->when(\call_user_func_array(array($loop, $method), $arguments))
            ->then()
                ->mock($baseLoopMock)
                    ->call($method)
        ;
        \call_user_func_array(array($methodCall, 'withArguments'), $arguments);
        $methodCall->once();
    }
}
