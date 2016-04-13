<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units;

use Cubiche\Core\Async\Deferred;
use Cubiche\Core\Async\DeferredInterface;

/**
 * DeferredTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DeferredTests extends PromiseTestCase
{
    /**
     * @var DeferredInterface
     */
    protected $deferred;

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\PromiseTestCase::promise()
     */
    protected function promise()
    {
        $this->deferred = Deferred::defer();

        return $this->deferred->promise();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\PromiseTestCase::resolve()
     */
    protected function resolve($value = null)
    {
        $this->deferred->resolve($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\PromiseTestCase::reject()
     */
    protected function reject($reason = null)
    {
        $this->deferred->reject($reason);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\PromiseTestCase::notify()
     */
    protected function notify($state = null)
    {
        $this->deferred->notify($state);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\PromiseTestCase::cancel()
     */
    protected function cancel()
    {
        return $this->deferred->cancel();
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
                ->isInstanceOf(DeferredInterface::class)
                ->isNotIdenticalTo(Deferred::defer())
        ;
    }
}
