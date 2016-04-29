<?php

/**
 * This file is part of the Cubiche/Async component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Tests\Units\Loop\Timer;

use Cubiche\Core\Async\Tests\Units\Promise\PromiseInterfaceTestCase;
use React\EventLoop\Factory;

/**
 * Timer Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class TimerTests extends PromiseInterfaceTestCase
{
    /**
     * Test interval,iterations,maxIterations and isActive methods.
     */
    public function testAccessMethods()
    {
        $this
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
            ->given($timer = $this->newDefaultTestedInstance())
            ->then()
                ->float($timer->interval())
                    ->isEqualTo(0.001)
                ->integer($timer->iterations())
                    ->isEqualTo(0)
                ->integer($timer->maxIterations())
                    ->isEqualTo(1)
                ->boolean($timer->isActive())
                    ->isTrue()
        ;
    }

    /**
     * Test __construct.
     */
    public function testConstructor()
    {
        $this
            ->exception(function () {
                $this->newTestedInstance(
                    Factory::create(),
                    $this->delegateMock(),
                    0.001,
                    true,
                    'foo'
                );
            })->isInstanceOf(\InvalidArgumentException::class);
    }

    /**
     * Test Timeout method.
     */
    public function testTimeout()
    {
        $this
            ->given(
                $loop = Factory::create(),
                $task = $this->delegateMockWithReturn('foo'),
                $onFulfilled = $this->delegateMock()
            )
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
            ->let($timer = $this->newTestedInstance($loop, $task, 0.001))
            ->when(function () use ($loop, $timer, $onFulfilled) {
                $timer->then($onFulfilled);
                $loop->run();
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
                $loop = Factory::create(),
                $task = $this->delegateMockWithReturn('foo'),
                $onRejected = $this->delegateMock(),
                $onNotify = $this->delegateMock()
            )
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
            ->let($timer = $this->newTestedInstance($loop, $task, 0.001, true, 2))
            /* @var \Cubiche\Core\Async\Loop\Timer\TimerInterface $timer */
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
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(
            Factory::create(),
            function () {
                return 'foo';
            },
            0.001,
            true,
            1,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function promiseDataProvider()
    {
        $timeout = $this->newTestedInstance(
            $loop = Factory::create(),
            function () {
                return $this->defaultResolveValue();
            },
            0.001
        );
        $loop->run();

        $timer = $this->newTestedInstance(
            $loop = Factory::create(),
            function () {
                throw  $this->defaultRejectReason();
            },
            0.001,
            true,
            1
        );

        $loop->run();

        return array(
            array($this->newDefaultTestedInstance()),
            array($timeout),
            array($timer),
        );
    }
}
