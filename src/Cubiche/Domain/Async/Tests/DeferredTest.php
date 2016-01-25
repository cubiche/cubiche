<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Async\Tests;

use Cubiche\Domain\Async\Deferred;
use Cubiche\Domain\Async\DeferredInterface;

/**
 * Deferred Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DeferredTest extends PromiseTestCase
{
    /**
     * @var DeferredInterface
     */
    protected $deferred;

    /**
     * {@inheritdoc}
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->deferred = Deferred::defer();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::promise()
     */
    protected function promise()
    {
        return $this->deferred->promise();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::resolve()
     */
    protected function resolve($value = null)
    {
        $this->deferred->resolve($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::reject()
     */
    protected function reject($reason = null)
    {
        $this->deferred->reject($reason);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::notify()
     */
    protected function notify($state = null)
    {
        $this->deferred->notify($state);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::cancel()
     */
    protected function cancel()
    {
        return $this->deferred->cancel();
    }

    /**
     * @test
     */
    public function testDefer()
    {
        $deferred = Deferred::defer();
        $this->assertInstanceOf(DeferredInterface::class, $deferred);
        $this->assertFalse($deferred === Deferred::defer());
    }
}
