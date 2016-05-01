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
 * Observable Resolver Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ObservableResolverTests extends ObservableResolverTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testResolve()
    {
        parent::testResolve();

        $this->innerFailureTest(function (callable $resolveCallback, callable $rejectCallback) {
            /** @var \Cubiche\Core\Async\Promise\ObservableResolver $resolver */
            $resolver = $this->newTestedInstance($resolveCallback, $rejectCallback);
            $resolver->resolve();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function testNotify()
    {
        parent::testNotify();

        $this->innerFailureTest(function (callable $notifyCallback, callable $rejectCallback) {
            /** @var \Cubiche\Core\Async\Promise\ObservableResolver $resolver */
            $resolver = $this->newTestedInstance(null, $rejectCallback, $notifyCallback);
            $resolver->notify();
        });
    }

    /**
     * @param callable $when
     */
    protected function innerFailureTest(callable $when)
    {
        $this
            ->given(
                $reason = new \Exception(),
                $callback = $this->delegateMockWithException($reason),
                $rejectCallback = $this->delegateMock()
            )
            ->when($when($callback, $rejectCallback))
                ->then()
                ->delegateCall($rejectCallback)
                    ->withArguments($reason)
                    ->once()
            ;
    }
}
