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
 * Rejected Promise class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class RejectedPromiseTests extends PromiseInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array($this->defaultRejectReason());
    }

    /**
     * {@inheritdoc}
     */
    protected function promiseDataProvider()
    {
        return array(
            array($this->newDefaultTestedInstance()),
        );
    }
}
