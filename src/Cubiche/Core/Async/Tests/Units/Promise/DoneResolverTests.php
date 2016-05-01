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
 * Done Resolver Tests class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DoneResolverTests extends ObservableResolverTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testResolve()
    {
        parent::testResolve();

        $this->innerFailureTest(function (callable $onFulfilled) {
            /** @var \Cubiche\Core\Async\Promise\DoneResolver $resolver */
            $resolver = $this->newTestedInstance($onFulfilled);
            $resolver->resolve();
        });
    }

    /**
     * Test reject.
     */
    public function testReject()
    {
        parent::testReject();

        $this->innerFailureTest(function (callable $onRejected) {
            /** @var \Cubiche\Core\Async\Promise\DoneResolver $resolver */
            $resolver = $this->newTestedInstance(null, $onRejected);
            $resolver->reject();
        });
    }

    /**
     * Test notify.
     */
    public function testNotify()
    {
        parent::testNotify();

        $this->innerFailureTest(function (callable $onNotify) {
            /** @var \Cubiche\Core\Async\Promise\DoneResolver $resolver */
            $resolver = $this->newTestedInstance(null, null, $onNotify);
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
                $callback = $this->delegateMockWithException($reason)
            )
            ->exception(function () use ($when, $callback) {
                $when($callback);
            })
        ;
    }
}
