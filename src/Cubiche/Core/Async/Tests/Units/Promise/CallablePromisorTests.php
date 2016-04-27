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

/**
 * Callable Promisor Test class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CallablePromisorTests extends PromisorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(function () {
            return 'foo';
        });
    }

    /**
     * Test __invoke.
     */
    public function testInvoke()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\CallablePromisor $promisor */
                $promisor = $this->newDefaultTestedInstance(),
                $onFulfilled = $this->delegateMock()
            )
            ->when(function () use ($promisor, $onFulfilled) {
                $promisor();
                $promisor->promise()->then($onFulfilled);
            })
            ->then()
                ->delegateCall($onFulfilled)
                    ->withArguments('foo')
                    ->once()
        ;

        $this
            ->given(
                $reason = new \Exception(),
                /** @var \Cubiche\Core\Async\Promise\CallablePromisor $promisor */
                $promisor = $this->newTestedInstance(function () use ($reason) {
                    throw $reason;
                }),
                $onRejected = $this->delegateMock()
            )
            ->when(function () use ($promisor, $onRejected) {
                $promisor();
                $promisor->promise()->then(null, $onRejected);
            })
            ->then()
                ->delegateCall($onRejected)
                    ->withArguments($reason)
                    ->once()
        ;
    }
}
