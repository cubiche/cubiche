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

/**
 * Observable Resolver Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ObservableResolverTestCase extends ResolverTestCase
{
    /**
     * Test resolve.
     */
    public function testResolve()
    {
        $this
            ->given(
                $resolveCallback = $this->delegateMockWithReturn('bar'),
                /** @var \Cubiche\Core\Async\Promise\ObservableResolver $resolver */
                $resolver = $this->newTestedInstance($resolveCallback)
            )
            ->when($resolver->resolve('foo'))
            ->then()
                ->delegateCall($resolveCallback)
                    ->withArguments('foo')
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
                $reason = new \Exception(),
                $rejectCallback = $this->delegateMock(),
                /** @var \Cubiche\Core\Async\Promise\ObservableResolver $resolver */
                $resolver = $this->newTestedInstance(null, $rejectCallback)
            )
            ->when($resolver->reject($reason))
            ->then()
                ->delegateCall($rejectCallback)
                    ->withArguments($reason)
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
                $notifyCallback = $this->delegateMock(),
                /** @var \Cubiche\Core\Async\Promise\ObservableResolver $resolver */
                $resolver = $this->newTestedInstance(null, null, $notifyCallback)
            )
            ->when($resolver->notify('foo'))
            ->then()
                ->delegateCall($notifyCallback)
                    ->withArguments('foo')
                    ->once()
        ;
    }
}
