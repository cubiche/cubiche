<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Async\Tests\Units;

use Cubiche\Domain\Async\Promise;
use Cubiche\Domain\Async\PromiseInterface;
use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations\extractor;
use mageekguy\atoum\asserter\generator;
use mageekguy\atoum\mock;
use mageekguy\atoum\test\assertion\manager;

/**
 * PromiseTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PromiseTests extends TestCase
{
    /**
     * @var PromiseInterface
     */
    protected $promise;

    /**
     * @var Delegate
     */
    protected $resolve;

    /**
     * @var Delegate
     */
    protected $reject;

    /**
     * @var Delegate
     */
    protected $notify;

    /**
     * @var Delegate
     */
    protected $cancel;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        adapter $adapter = null,
        extractor $annotationExtractor = null,
        generator $asserterGenerator = null,
        manager $assertionManager = null,
        \closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $test = $this;

        $this->getAssertionManager()
            ->setHandler(
                'promise',
                function () use ($test) {
                    return new Promise(
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->resolve = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->reject = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->notify = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->cancel = $delegate;
                        })
                    );
                }
            )
            ->setHandler(
                'resolve',
                function ($value = null) use ($test) {
                    $test->resolve->__invoke($value);
                }
            )
            ->setHandler(
                'reject',
                function ($value = null) use ($test) {
                    $test->reject->__invoke($value);
                }
            )
            ->setHandler(
                'notify',
                function ($value = null) use ($test) {
                    $test->notify->__invoke($value);
                }
            )
            ->setHandler(
                'cancel',
                function ($value = null) use ($test) {
                    $test->cancel->__invoke($value);
                }
            )
            ->setHandler(
                'delegateMock',
                function () {
                    return new \mock\Cubiche\Domain\Delegate\Delegate(function ($value = null) {
                    });
                }
            )
            ->setHandler(
                'delegateCall',
                function (mock\aggregator $mock) use ($test) {
                    return $test
                        ->MockBuilder($mock)
                        ->call('__invoke')
                    ;
                }
            )
        ;
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

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($promise = $this->promise())
            ->then
                ->object($promise)
                    ->isInstanceOf(Promise::class)
                ->object($this->resolve)
                    ->isInstanceOf(Delegate::class)
                ->object($this->reject)
                    ->isInstanceOf(Delegate::class)
                ->object($this->notify)
                    ->isInstanceOf(Delegate::class)
                ->object($this->cancel)
                    ->isInstanceOf(Delegate::class)
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
            ->if($promiseThen = $promise->then($succeed, $rejected, $notify))
            ->when($this->resolve('foo'))
            ->then
                ->object($promiseThen)
                    ->isInstanceOf(Promise::class)
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

        $test = $this;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when(function () use ($test) {
                for ($i = 0; $i < 10; ++$i) {
                    $test->notify(($i + 1) * 10);
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
                    ->isInstanceOf(Promise::class)
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
                    ->isInstanceOf(Promise::class)
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
                    ->isInstanceOf(Promise::class)
                ->delegateCall($finally)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($notify)
                    ->never()
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

        $this
            ->given(
                $promise = $this->promise(),
                $notify = $this->delegateMock(),
                $finally = $this->delegateMock()
            )
            ->if($promiseAlways = $promise->always($finally, $notify))
            ->when($this->notify('bar'))
            ->then
                ->delegateCall($finally)
                    ->never()
                ->delegateCall($notify)
                    ->withArguments('bar')
                    ->once()
        ;
    }

    /*
     * Test cancel.
     */
    public function testCancel()
    {
        $test = $this;

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
                        ->arguments(0, function ($argument) use ($test) {
                            $test->object($argument)->isInstanceOf(\RuntimeException::class);
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
            ->if($promiseThen = $promise->then($succeed, $rejected, $notify))
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
        $test = $this;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () use ($test) {
                    $test->resolve();
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
                function () use ($test) {
                    $test->reject();
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
                function () use ($test) {
                    $test->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }

    /*
     * Test rejected promise.
     */
    public function testRejectedPromise()
    {
        $test = $this;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () use ($test) {
                    $test->resolve();
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
                function () use ($test) {
                    $test->reject();
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
                function () use ($test) {
                    $test->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }
}
