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

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Async\Promise\Promise;
use Cubiche\Core\Async\Promise\State;

/**
 * Promise Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromiseTests extends PromiseInterfaceTestCase
{
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
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(
            function (callable $callable) {
                $this->resolve = new Delegate($callable);
            },
            function (callable $callable) {
                $this->reject = new Delegate($callable);
            },
            function (callable $callable) {
                $this->notify = new Delegate($callable);
            },
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function resolve($value = null)
    {
        $this->resolve->__invoke($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function reject($reason = null)
    {
        $this->reject->__invoke($reason);
    }

    /**
     * {@inheritdoc}
     */
    protected function notify($state = null)
    {
        $this->notify->__invoke($state);
    }

    /**
     * Test __construct.
     */
    public function testConstruct()
    {
        $this
            ->given(
                $resolve = $this->delegateMock(),
                $reject = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->when($this->newTestedInstance($resolve, $reject, $notify))
            ->then()
                ->delegateCall($resolve)
                    ->once()
                ->delegateCall($reject)
                    ->once()
                ->delegateCall($notify)
                    ->once()
        ;

        $this
            /* @var \Cubiche\Core\Async\Promise\PromiseInterface $promise */
            ->given($promise = $this->newDefaultTestedInstance())
            ->then()
                ->boolean($promise->state()->equals(State::PENDING()))
                    ->isTrue()
        ;
    }

    /**
     * Test notify.
     */
    public function testNotify()
    {
        $this
            ->given(
                $promise = $this->newDefaultTestedInstance(),
                $onNotify = $this->delegateMock()
            )
            ->when(function () use ($promise, $onNotify) {
                $promise->then(null, null, $onNotify);
                $this->notify('foo');
            })
            ->then()
                ->delegateCall($onNotify)
                    ->withArguments('foo')
                    ->once()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function promiseDataProvider()
    {
        $pending = $this->newDefaultTestedInstance();

        $fulfilled = $this->newDefaultTestedInstance();
        $this->resolve($this->defaultResolveValue());

        $rejected = $this->newDefaultTestedInstance();
        $this->reject($this->defaultRejectReason());

        return array(
            'pending' => array($pending),
            'fulfilled' => array($fulfilled),
            'rejected' => array($rejected),
        );
    }
}
