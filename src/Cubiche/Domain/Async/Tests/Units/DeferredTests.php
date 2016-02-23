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

use Cubiche\Domain\Async\Deferred;
use Cubiche\Domain\Async\DeferredInterface;
use Cubiche\Domain\Async\Promise;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations\extractor;
use mageekguy\atoum\asserter\generator;
use mageekguy\atoum\mock;
use mageekguy\atoum\test\assertion\manager;

/**
 * DeferredTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DeferredTests extends PromiseTestCase
{
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

        $this->getAssertionManager()
            ->setHandler(
                'deferredMock',
                function () {
                    return \mock\Cubiche\Domain\Async\Deferred::defer();
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
            ->implements(DeferredInterface::class)
        ;
    }

    /*
     * Test defer method.
     */
    public function testDefer()
    {
        $this
            ->given($deferred = Deferred::defer())
            ->then
                ->object($deferred)
                ->isInstanceOf(Deferred::class)
                ->isNotIdenticalTo(Deferred::defer())
        ;
    }

    /*
     * Test promise method.
     */
    public function testPromise()
    {
        $this
            ->given($deferred = Deferred::defer())
            ->when($promise = $deferred->promise())
            ->then
                ->object($promise)
                ->isInstanceOf(Promise::class)
        ;
    }

    /*
     * Test resolve method.
     */
    public function testResolve()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $deferred = $this->deferredMock()
            )
            ->when(
                $this->invoke($promise)->addSucceedDelegate($deferred, $succeed)
            )
            ->and($this->resolve('bar'))
            ->then
                ->mock($deferred)
                    ->call('resolve')
                        ->withArguments('bar')
                        ->once()
        ;
    }

    /*
     * Test reject method.
     */
    public function testReject()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $rejected = $this->delegateMock(),
                $deferred = $this->deferredMock()
            )
            ->when(
                $this->invoke($promise)->addRejectedDelegate($deferred, $rejected)
            )
            ->and($this->reject($reason = new \Exception()))
            ->then
                ->mock($deferred)
                    ->call('reject')
                        ->withArguments($reason)
                        ->once()
        ;
    }

    /*
     * Test notify method.
     */
    public function testNotify()
    {
        $this
            ->given($deferred = Deferred::defer())
            ->when($deferred->resolve())
            ->then
                ->object($deferred->promise())
                ->isInstanceOf(Promise::class)
        ;

        $this
            ->given($deferred = Deferred::defer())
            ->when($deferred->reject())
            ->then
                ->object($deferred->promise())
                ->isInstanceOf(Promise::class)
        ;

        $this
            ->given($deferred = Deferred::defer())
            ->when($deferred->notify())
            ->then
                ->object($deferred->promise())
                ->isInstanceOf(Promise::class)
        ;

        $this
            ->given($deferred = Deferred::defer())
            ->when($deferred->cancel())
            ->then
            ->object($deferred->promise())
            ->isInstanceOf(Promise::class)
        ;
    }
}
