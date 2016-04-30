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

use Cubiche\Core\Async\Promise\ResolverInterface;
use Cubiche\Core\Async\Promise\Deferred;
use Cubiche\Core\Async\Promise\RejectedPromise;

/**
 * Resolver Proxy Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ResolverProxyTests extends ResolverInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(
            $this->newMockInstance(ResolverInterface::class),
            function () {
            },
            function () {
            },
            function () {
            },
        );
    }

    /**
     * Test resolve.
     */
    public function testResolve()
    {
        $this
            ->given(
                $target = $this->newMockInstance(ResolverInterface::class),
                $onFulfilled = $this->delegateMockWithReturn('bar'),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, $onFulfilled)
            )
            ->when($resolver->resolve('foo'))
            ->then()
                ->mock($target)
                    ->call('resolve')
                        ->withArguments('bar')
                        ->once()
                ->delegateCall($onFulfilled)
                    ->withArguments('foo')
                    ->once()
        ;

        $this
            ->given(
                $target = $this->newMockInstance(ResolverInterface::class),
                $deferred = new Deferred(),
                $onFulfilled = $this->delegateMockWithReturn($deferred->promise()),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, $onFulfilled)
            )
            ->when($resolver->resolve('foo'))
            ->then()
                ->mock($target)
                    ->call('resolve')
                        ->never()
        ;

        $this
            ->when(function () use ($deferred) {
                $deferred->notify('state');
            })
            ->then()
                ->mock($target)
                    ->call('notify')
                        ->withArguments('state')
                        ->once()
        ;

        $this
            ->when(function () use ($deferred) {
                $deferred->resolve('foo');
            })
            ->then()
                ->mock($target)
                    ->call('resolve')
                        ->withArguments('foo')
                        ->once()
        ;

        $this
            ->given(
                $target = $this->newMockInstance(ResolverInterface::class),
                $reason = new \Exception(),
                $onFulfilled = $this->delegateMockWithReturn(new RejectedPromise($reason)),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, $onFulfilled)
            )
            ->when($resolver->resolve())
            ->then()
                ->mock($target)
                    ->call('reject')
                        ->withArguments($reason)
                        ->once()
        ;

        $this
            ->given(
                $target = $this->newMockInstance(ResolverInterface::class),
                $reason = new \Exception(),
                $onFulfilled = $this->delegateMockWithException($reason),
                $onRejected = $this->delegateMock(),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, $onFulfilled, $onRejected)
            )
            ->when($resolver->resolve())
            ->then()
                ->mock($target)
                    ->call('reject')
                        ->withArguments($reason)
                        ->once()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
        ;
    }

    /**
     * Test reject.
     */
    public function testReject()
    {
        $this
            ->given(
                $reason1 = new \Exception(),
                $reason2 = new \Exception(),
                $target = $this->newMockInstance(ResolverInterface::class),
                $onRejected = $this->delegateMockWithException($reason2),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, null, $onRejected)
            )
            ->when($resolver->reject($reason1))
            ->then()
                ->mock($target)
                    ->call('reject')
                        ->withArguments($reason2)
                        ->once()
                ->delegateCall($onRejected)
                    ->withArguments($reason1)
                    ->once()
        ;
    }

    /**
     * Test notify.
     */
    public function testNotify()
    {
        $this
            ->given(
                $reason = new \Exception(),
                $target = $this->newMockInstance(ResolverInterface::class),
                $onNotify = $this->delegateMockWithException($reason),
                /** @var \Cubiche\Core\Async\Promise\ResolverProxy $resolver */
                $resolver = $this->newTestedInstance($target, null, null, $onNotify)
            )
            ->when($resolver->notify('foo'))
            ->then()
                ->mock($target)
                    ->call('notify')
                        ->never()
                ->mock($target)
                     ->call('reject')
                        ->withArguments($reason)
                        ->once()
                ->delegateCall($onNotify)
                    ->withArguments('foo')
                    ->once()
        ;
    }
}
