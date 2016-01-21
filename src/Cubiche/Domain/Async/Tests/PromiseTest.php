<?php

/**
 * This file is part of the cubiche/cubiche project.
 */
namespace Cubiche\Domain\Async\Tests;

use Cubiche\Domain\Async\Promise;
use Cubiche\Domain\System\Delegate;

/**
 * PromiseTest.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromiseTest extends PromiseTestCase
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
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->resolve = null;
        $this->reject = null;
        $this->notify = null;
        $this->cancel = null;

        $this->promise = new Promise(
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
     * @see \Cubiche\Domain\Async\Tests\PromiseTestCase::promise()
     */
    protected function promise()
    {
        return $this->promise;
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
     * @test
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Delegate::class, $this->resolve);
        $this->assertInstanceOf(Delegate::class, $this->reject);
        $this->assertInstanceOf(Delegate::class, $this->notify);
        $this->assertInstanceOf(Delegate::class, $this->cancel);
    }
}
