<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units;

use Cubiche\Tests\TestCase;
use Cubiche\Core\Async\PromiseInterface;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\mock\aggregator as MockAggregator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * PromiseTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class PromiseTestCase extends TestCase
{
    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param Closure   $reflectionClassFactory
     * @param Closure   $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null,
        \Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAssertionManager()
            ->setHandler(
                'promise',
                function () {
                    return $this->promise();
                }
            )
            ->setHandler(
                'resolve',
                function ($value = null) {
                    return $this->resolve($value);
                }
            )
            ->setHandler(
                'reject',
                function ($reason = null) {
                    return $this->reject($reason);
                }
            )
            ->setHandler(
                'notify',
                function ($state = null) {
                    return $this->notify($state);
                }
            )
            ->setHandler(
                'cancel',
                function () {
                    return $this->cancel();
                }
            )
            ->setHandler(
                'delegateMock',
                function () {
                    $this->delegateMock();
                }
            )
            ->setHandler(
                'delegateMockWithReturn',
                function ($return) {
                    $this->delegateMockWithReturn($return);
                }
            )
            ->setHandler(
                'delegateCall',
                function (MockAggregator $mock) {
                    return $this->delegateCall($mock);
                }
            )
        ;
    }

    /**
     * @return PromiseInterface
     */
    abstract protected function promise();

    /**
     * @param mixed $value
     */
    abstract protected function resolve($value = null);

    /**
     * @param mixed $reason
     */
    abstract protected function reject($reason = null);

    /**
     * @param mixed $state
     */
    abstract protected function notify($state = null);

    /**
     * @return bool
     */
    abstract protected function cancel();

    /*
     * Test then.
     */
    public function testThen()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->resolve('foo'))
            ->then
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->delegateCall($succeed)
                    ->never()
                ->delegateCall($rejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when(function () {
                for ($i = 0; $i < 10; ++$i) {
                    $this->notify(($i + 1) * 10);
                }
            })
            ->then
                ->delegateCall($succeed)
                    ->never()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->exactly(10)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMockWithReturn('bar'),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock(),
                $succeedThen = $this->delegateMock()
            )
            ->if($promiseThen = $promise->then($succeed, $rejected, $notify))
            ->and($promiseThen->then($succeedThen))
            ->when($this->resolve('foo'))
            ->then
                ->object($promiseThen)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
                ->delegateCall($succeedThen)
                    ->withArguments('bar')
                    ->once()
        ;
    }

    /*
     * Test otherwise.
     */
    public function testOtherwise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $otherwise = $this->delegateMock()
            )
            ->if($promiseOtherwise = $promise->otherwise($otherwise))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->object($promiseOtherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($otherwise)
                    ->withArguments($reason)
                    ->once()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock(),
                $otherwise = $this->delegateMock()
            )
            ->if(
                $promiseOtherwise = $promise
                    ->then($succeed, $rejected, $notify)
                    ->otherwise($otherwise)
            )
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->object($promiseOtherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($rejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($otherwise)
                    ->withArguments($reason)
                    ->once()
        ;
    }

    /*
     * Test always.
     */
    public function testAlways()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $notify = $this->delegateMock(),
                $finally = $this->delegateMock()
            )
            ->if($promiseAlways = $promise->always($finally, $notify))
            ->when($this->resolve('foo'))
            ->then
                ->object($promiseAlways)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($finally)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $notify = $this->delegateMock(),
                $finally = $this->delegateMock()
            )
            ->if($promiseAlways = $promise->always($finally, $notify))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->delegateCall($finally)
                    ->withArguments(null, $reason)
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /*
     * Test cancel.
     */
    public function testCancel()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $otherwise = $this->delegateMock()
            )
            ->if($promise->otherwise($otherwise))
            ->when($this->cancel())
            ->then
                ->delegateCall($otherwise)
                    ->with()
                    ->arguments(0, function ($argument) {
                        $this->object($argument)->isInstanceOf(\RuntimeException::class);
                    })
                    ->once()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->resolve('foo'))
            ->and($this->cancel())
            ->then
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /*
     * Test resolved promise.
     */
    public function testResolvedPromise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->resolve();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->reject();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }

    /*
     * Test rejected promise.
     */
    public function testRejectedPromise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->resolve();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->reject();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }

    /**
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMock($return = null)
    {
        $mockName = '\mock\Cubiche\Core\Delegate\Delegate';

        return new $mockName(function ($value = null) use ($return) {
            return $return === null ? $value : $return;
        });
    }

    /**
     * @param mixed $return
     *
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMockWithReturn($return)
    {
        return $this->delegateMock($return);
    }

    /**
     * @param MockAggregator $mock
     *
     * @return mixed
     */
    protected function delegateCall(MockAggregator $mock)
    {
        return $this->mock($mock)->call('__invoke');
    }
}
