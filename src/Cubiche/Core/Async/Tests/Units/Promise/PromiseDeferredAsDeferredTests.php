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
 * Promise Deferred As Deferred Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PromiseDeferredAsDeferredTests extends DeferredInterfaceTestCase
{
    /**
     * @return object
     */
    public function newDefaultTestedInstance()
    {
        /** @var \Cubiche\Core\Async\Promise\PromiseDeferred $instance */
        $instance = parent::newDefaultTestedInstance();
        $instance->then();

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestedClassName()
    {
        return PromiseDeferred::class;
    }
}
