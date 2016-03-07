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

/**
 * PromiseTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PromiseTests extends PromiseTestCase
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
     * @var Delegate
     */
    protected $cancel;

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::promise()
     */
    protected function promise()
    {
        return new Promise(
            new Delegate(function (Delegate $delegate) {
                $this->resolve = $delegate;
            }),
            new Delegate(function (Delegate $delegate) {
                $this->reject = $delegate;
            }),
            new Delegate(function (Delegate $delegate) {
                $this->notify = $delegate;
            }),
            new Delegate(function (Delegate $delegate) {
                $this->cancel = $delegate;
            })
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::resolve()
     */
    protected function resolve($value = null)
    {
        $this->resolve->__invoke($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::reject()
     */
    protected function reject($reason = null)
    {
        $this->reject->__invoke($reason);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::notify()
     */
    protected function notify($state = null)
    {
        $this->notify->__invoke($state);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::cancel()
     */
    protected function cancel()
    {
        return $this->cancel->__invoke();
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
                    ->isInstanceOf(PromiseInterface::class)
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
}
