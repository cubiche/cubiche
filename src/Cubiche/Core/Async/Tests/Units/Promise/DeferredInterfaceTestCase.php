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

use Cubiche\Core\Async\Promise\Deferred;
use Cubiche\Core\Async\Promise\DeferredInterface;

/**
 * Deferred Interface Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DeferredInterfaceTestCase extends PromiseInterfaceTestCase
{
    /**
     * @var DeferredInterface
     */
    protected $deferred;

    /**
     * {@inheritdoc}
     */
    protected function promise()
    {
        $this->deferred = Deferred::defer();

        return $this->deferred->promise();
    }

    /**
     * {@inheritdoc}
     */
    protected function resolve($value = null)
    {
        $this->deferred->resolve($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function reject($reason = null)
    {
        $this->deferred->reject($reason);
    }

    /**
     * {@inheritdoc}
     */
    protected function notify($state = null)
    {
        $this->deferred->notify($state);
    }

    /**
     * {@inheritdoc}
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
}
