<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Handler\Locator;

use Cubiche\Core\Bus\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessageListener;

/**
 * InMemoryLocator class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class InMemoryLocatorTests extends HandlerLocatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createHandlerLocator()
    {
        return new InMemoryLocator();
    }
}
