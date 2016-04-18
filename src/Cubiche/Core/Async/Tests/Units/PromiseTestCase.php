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

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Async\Promise;

/**
 * Promise Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PromiseTestCase extends PromiseInterfaceTestCase
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
     * @see \Cubiche\Tests\TestCase::defaultConstructorArguments()
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
            function (callable $callable) {
                $this->cancel = new Delegate($callable);
            },
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\Units\PromiseInterfaceTestCase::promise()
     */
    protected function promise()
    {
        return $this->newDefaultTestedInstance();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\Units\PromiseInterfaceTestCase::resolve()
     */
    protected function resolve($value = null)
    {
        $this->resolve->__invoke($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\Units\PromiseInterfaceTestCase::reject()
     */
    protected function reject($reason = null)
    {
        $this->reject->__invoke($reason);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\Units\PromiseInterfaceTestCase::notify()
     */
    protected function notify($state = null)
    {
        $this->notify->__invoke($state);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Async\Tests\Units\PromiseInterfaceTestCase::cancel()
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
        parent::testClass();

        if ($this->getTestedClassName() !== Promise::class) {
            $this
                ->testedClass
                    ->isSubClassOf(Promise::class);
        }
    }
}
