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

/**
 * Deferred Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DeferredTests extends DeferredInterfaceTestCase
{
    /**
     * Test cancel.
     */
    public function testCancel()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance()
            )
            ->when($canceled = $deferred->cancel())
            ->then()
                ->boolean($canceled)
                    ->isTrue()
        ;

        $this
            ->given($onRejected = $this->delegateMock())
            ->when($deferred->promise()->then(null, $onRejected))
            ->then()
                ->delegateCall($onRejected)
                    ->once()
        ;

        $this
            ->given(
                /** @var \Cubiche\Core\Async\Promise\DeferredInterface $deferred */
                $deferred = $this->newDefaultTestedInstance()
            )
            ->if($deferred->resolve('foo'))
            ->when($canceled = $deferred->cancel())
                ->then()
                    ->boolean($canceled)
                        ->isFalse()
            ;
    }
}
