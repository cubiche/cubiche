<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Publisher;

use Cubiche\Core\Bus\MessageInterface;

/**
 * MessagePublisher interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessagePublisherInterface
{
    /**
     * @param MessageInterface $message
     */
    public function publishMessage(MessageInterface $message);
}
