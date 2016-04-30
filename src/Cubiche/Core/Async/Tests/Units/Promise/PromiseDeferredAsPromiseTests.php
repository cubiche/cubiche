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

use Cubiche\Core\Async\Promise\PromiseDeferred;

/**
 * Promise Deferred Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromiseDeferredAsPromiseTests extends PromiseInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function promiseDataProvider()
    {
        $pending = $this->newDefaultTestedInstance();

        $fulfilled = $this->newDefaultTestedInstance();
        $fulfilled->resolve($this->defaultResolveValue());

        $rejected = $this->newDefaultTestedInstance();
        $rejected->reject($this->defaultRejectReason());

        return array(
            'pending' => array($pending),
            'fulfilled' => array($fulfilled),
            'rejected' => array($rejected),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTestedClassName()
    {
        return PromiseDeferred::class;
    }
}
