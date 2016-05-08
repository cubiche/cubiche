<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Middlewares\Handler\Locator;

use Cubiche\Core\MessageBus\Exception\NotFoundException;

/**
 * Locator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface LocatorInterface
{
    /**
     * Retrieves the handler for a specified command.
     *
     * @param string $commandName
     *
     * @return object
     *
     * @throws NotFoundException
     */
    public function locate($commandName);
}
