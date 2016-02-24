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

use Cubiche\Domain\Model\Tests\TestCase;
use Cubiche\Domain\Async\PromiseInterface;
use Cubiche\Domain\Delegate\Delegate;

/**
 * Promise Test Case.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PromiseTestCase extends TestCase
{
    /**
     * @return PromiseInterface
     */
    abstract protected function promise();

    /**
     * @param mixed $value
     */
    abstract protected function resolve($value = null);

    /**
     * @param mixed $reason
     */
    abstract protected function reject($reason = null);

    /**
     * @param mixed $state
     */
    abstract protected function notify($state = null);

    /**
     * @return bool
     */
    abstract protected function cancel();

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function expectDelegateNever()
    {
        $mock = $this->createDelegateMock();
        $mock
            ->expects($this->never())
            ->method('__invoke');

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createDelegateMock()
    {
        return $this->getMock(Delegate::class, array(), array(function () {

        }));
    }

    /**
     * @param mixed $param
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function expectDelegateOnce($param, $return = null)
    {
        $mock = $this->createDelegateMock();
        $mock
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($param))
            ->willReturn($return);

        return $mock;
    }

    /**
     * @test
     */
    public function thenSucceed()
    {
        $thenPromise = $this->promise()->then($this->expectDelegateOnce('foo', 'bar'));
        $this->assertInstanceOf(PromiseInterface::class, $thenPromise);

        $thenPromise->then(
            $this->expectDelegateOnce('bar'),
            $this->expectDelegateNever(),
            $this->expectDelegateNever()
        )->always($this->expectDelegateOnce('bar'));

        $this->resolve('foo');

        $this->promise()->then($this->expectDelegateOnce('foo'));
    }

    /**
     * @test
     */
    public function thenRejected()
    {
        $reason = new \Exception();
        $this->promise()->then($this->expectDelegateNever(), $this->expectDelegateOnce($reason));
        $this->reject($reason);
        $this->promise()->then(null, $this->expectDelegateOnce($reason));
    }

    /**
     * @test
     */
    public function thenNotify()
    {
        $notify = $this->createDelegateMock();
        for ($i = 0; $i < 10; ++$i) {
            $notify
                ->expects($this->at($i))
                ->method('__invoke')
                ->with($this->identicalTo(($i + 1) * 10));
        }
        $this->promise()->then($this->expectDelegateNever(), $this->expectDelegateNever(), $notify);
        for ($i = 0; $i < 10; ++$i) {
            $this->notify(($i + 1) * 10);
        }
    }

    /**
     * @test
     */
    public function resolveCompletedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->resolve();
        $this->resolve();
    }

    /**
     * @test
     */
    public function rejectCompletedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->resolve();
        $this->reject();
    }

    /**
     * @test
     */
    public function notifyCompletedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->resolve();
        $this->notify();
    }

    /**
     * @test
     */
    public function resolveRejectedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->reject();
        $this->resolve();
    }

    /**
     * @test
     */
    public function rejectRejectedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->reject();
        $this->reject();
    }

    /**
     * @test
     */
    public function notifyRejectedPromise()
    {
        $this->setExpectedException(\LogicException::class);
        $this->reject();
        $this->notify();
    }

    /**
     * @test
     */
    public function otherwise()
    {
        $reason = new \Exception();
        $promise = $this->promise()->otherwise($this->expectDelegateOnce($reason));
        $this->assertInstanceOf(PromiseInterface::class, $promise);

        $this->reject($reason);

        $promise->then($this->expectDelegateNever(), $this->expectDelegateOnce($reason));
    }

    /**
     * @test
     */
    public function thenOtherwise()
    {
        $reason = new \Exception();
        $this->promise()->then()->otherwise($this->expectDelegateOnce($reason));
        $this->reject($reason);
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function alwaysCompletedByPromise(PromiseInterface $promise)
    {
        $finally = $this->createDelegateMock();
        $finally
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo('foo'), $this->identicalTo(null));

        $alwaysPromise = $promise->always($finally);
        $this->assertInstanceOf(PromiseInterface::class, $alwaysPromise);
        $this->resolve('foo');
    }

    /**
     * @param PromiseInterface $promise
     */
    protected function alwaysRejectedByPromise(PromiseInterface $promise)
    {
        $reason = new \Exception();
        $finally = $this->createDelegateMock();
        $finally
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(null), $this->identicalTo($reason));

        $promise->always($finally);

        $this->reject($reason);
    }

    /**
     * @test
     */
    public function alwaysCompleted()
    {
        $this->alwaysCompletedByPromise($this->promise());
    }

    /**
     * @test
     */
    public function alwaysRejected()
    {
        $this->alwaysRejectedByPromise($this->promise());
    }

    /**
     * @test
     */
    public function thenAlwaysCompleted()
    {
        $this->alwaysCompletedByPromise($this->promise()->then());
    }

    /**
     * @test
     */
    public function thenAlwaysRejected()
    {
        $this->alwaysRejectedByPromise($this->promise()->then());
    }

    /**
     * @test
     */
    public function testThenOtherwiseAlwaysCompleted()
    {
        $this->alwaysCompletedByPromise($this->promise()->then()->otherwise($this->expectDelegateNever()));
    }

    /**
     * @test
     */
    public function thenOtherwiseAlwaysRejected()
    {
        $reason = new \Exception();
        $finally = $this->createDelegateMock();
        $finally
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo(null), $this->identicalTo($reason));

        $this->promise()->then()->otherwise($this->expectDelegateOnce($reason))->always($finally);

        $this->reject($reason);
    }

    /**
     * @test
     */
    public function testAlwaysNofified()
    {
        $notify = $this->createDelegateMock();
        for ($i = 0; $i < 10; ++$i) {
            $notify
                ->expects($this->at($i))
                ->method('__invoke')
                ->with($this->identicalTo(($i + 1) * 10));
        }
        $this->promise()->always($this->expectDelegateNever(), $notify);
        for ($i = 0; $i < 10; ++$i) {
            $this->notify(($i + 1) * 10);
        }
    }

    /**
     * @test
     */
    public function cancelWaitingPromise()
    {
        $reject = $this->createDelegateMock();
        $reject
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(\RuntimeException::class));
        $this->promise()->then($this->expectDelegateNever())->otherwise($reject);

        $this->assertTrue($this->cancel());
    }

    /**
     * @test
     */
    public function cancelCompletedPromise()
    {
        $this->resolve('foo');
        $this->assertFalse($this->cancel());

        $this->promise()->then($this->expectDelegateOnce('foo'));
    }

    /**
     * @test
     */
    public function cancelRejectedPromise()
    {
        $reason = new \Exception();
        $this->reject($reason);
        $this->assertFalse($this->cancel());

        $this->promise()->otherwise($this->expectDelegateOnce($reason));
    }
}
