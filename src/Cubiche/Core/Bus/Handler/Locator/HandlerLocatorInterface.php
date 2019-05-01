<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;

/**
 * HandlerLocator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface HandlerLocatorInterface
{
    /**
     * Retrieves the handler for a specified name of message.
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function locate(string $messageName);

    /**
     * @param string $messageName
     * @param mixed  $handler
     */
    public function addHandler(string $messageName, $handler);
}
