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
                        ->mock($mock)
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
                $succeedMock = $this->delegateMock(),
                $rejectedMock = $this->delegateMock(),
                $notifyMock = $this->delegateMock()
            )
            ->if($promise->then($succeedMock, $rejectedMock, $notifyMock))
            ->when($this->resolve('foo'))
            ->then
                ->delegateCall($succeedMock)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejectedMock)
                    ->never()
                ->delegateCall($notifyMock)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeedMock = $this->delegateMock(),
                $rejectedMock = $this->delegateMock(),
                $notifyMock = $this->delegateMock()
            )
            ->if($promise->then($succeedMock, $rejectedMock, $notifyMock))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->delegateCall($succeedMock)
                    ->never()
                ->delegateCall($rejectedMock)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($notifyMock)
                    ->never()
        ;

        $test = $this;

        $this
            ->given(
                $promise = $this->promise(),
                $succeedMock = $this->delegateMock(),
                $rejectedMock = $this->delegateMock(),
                $notifyMock = $this->delegateMock()
            )
            ->if($promise->then($succeedMock, $rejectedMock, $notifyMock))
            ->when(function () use ($test) {
                for ($i = 0; $i < 10; ++$i) {
                    $test->notify(($i + 1) * 10);
                }
            })
            ->then
                ->delegateCall($succeedMock)
                   ->never()
                ->delegateCall($rejectedMock)
                    ->never()
                ->delegateCall($notifyMock)
                    ->exactly(10)
        ;
    }
}
