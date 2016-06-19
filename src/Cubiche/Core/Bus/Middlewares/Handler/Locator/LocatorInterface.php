<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;

/**
 * Locator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface LocatorInterface
{
    /**
     * Retrieves the handler for a specified name of message.
     *
     * @param string $nameOfMessage
     *
     * @return object
     *
     * @throws NotFoundException
     */
    public function locate($nameOfMessage);

    /**
     * @param string $nameOfMessage
     * @param mixed  $handler
     */
    public function addHandler($nameOfMessage, $handler);
}
