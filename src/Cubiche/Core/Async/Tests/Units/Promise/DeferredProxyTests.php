<?php

/**
 * This file is part of the Cubiche/Async component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units\Promise;

use Cubiche\Core\Async\Promise\Deferred;

/**
 * Deferred Proxy Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DeferredProxyTests extends DeferredInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(
            new Deferred(),
            function () {
            },
            function () {
            },
            function () {
            },
        );
    }

    /**
     * Test __construct.
     */
    public function testConstruct()
    {
        $this
            ->exception(function () {
                $deferred = new Deferred();
                $deferred->resolve();
                $this->newTestedInstance($deferred);
            })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function testReject()
    {
        parent::testReject();

        $this
            ->given(
                $reason = new \Exception(),
                $onRejected = $this->delegateMock(),
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newTestedInstance(
                    new Deferred(),
                    null,
                    function () use ($reason) {
                        throw $reason;
                    }
                )
            )
            ->when(function () use ($deferred, $onRejected) {
                $deferred->promise()->then(null, $onRejected);
                $deferred->reject('foo');
            })
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
            ;
    }
}
