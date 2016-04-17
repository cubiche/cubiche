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
use Cubiche\Tests\TestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\mock\aggregator as MockAggregator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;
use Cubiche\Core\Delegate\Delegate;

/**
 * Promise Interface Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PromiseInterfaceTestCase extends TestCase
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

        $this
            ->getAssertionManager()
                ->setHandler('delegateCall', function (MockAggregator $mock) {
                    return $this->delegateCall($mock);
                })
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
            ->when(function () use ($promise, $succeed, $rejected, $notify) {
                $promise->then($succeed, $rejected, $notify);
                $this->resolve('foo');
            })
            ->then()
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
                $notify = $this->delegateMock(),
                $reason = new \Exception()
            )
            ->when(function () use ($promise, $succeed, $rejected, $notify, $reason) {
                $promise->then($succeed, $rejected, $notify);
                $this->reject($reason);
            })
            ->then()
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
            ->when(function () use ($promise, $succeed, $rejected, $notify) {
                $promise->then($succeed, $rejected, $notify);
                for ($i = 0; $i < 10; ++$i) {
                    $this->notify(($i + 1) * 10);
                }
            })
            ->then()
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
            ->let($promiseThen = $promise->then($succeed, $rejected, $notify))
            ->when(function () use ($promiseThen, $succeedThen) {
                $promiseThen->then($succeedThen);
                $this->resolve('foo');
            })
            ->then()
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

    /**
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
            ->then()
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
            ->then()
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

    /**
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
            ->then()
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
            ->then()
                ->delegateCall($finally)
                    ->withArguments(null, $reason)
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /**
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
            ->then()
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
            ->then()
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /**
     * Test resolved promise.
     */
    public function testResolvedPromise()
    {
        $this->resolvedRejectedPromiseTest(function () {
            $this->resolve();
        });
    }

    /**
     * Test rejected promise.
     */
    public function testRejectedPromise()
    {
        $this->resolvedRejectedPromiseTest(function () {
            $this->reject(new \Exception());
        });
    }

    /**
     * @param callable $when
     */
    protected function resolvedRejectedPromiseTest(callable $when)
    {
        $this
        ->if($this->promise())
            ->when($when)
            ->then()
            ->exception(function () {
                $this->resolve();
            })->isInstanceOf(\LogicException::class)
            ->exception(function () {
                $this->reject();
            })->isInstanceOf(\LogicException::class)
            ->exception(function () {
                $this->notify();
            })->isInstanceOf(\LogicException::class)
            ;
    }

    /**
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMock($return = null)
    {
        $mockName = '\mock\\'.Delegate::class;

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
