<?php

/**
 * This file is part of the Cubiche/MessageBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus;

/**
 * MessageBus interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageBusInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return mixed
     */
    public function dispatch(MessageInterface $message);
}
