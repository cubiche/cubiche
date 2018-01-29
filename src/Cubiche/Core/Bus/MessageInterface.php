<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus;

/**
 * Message interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageInterface
{
    /**
     * @return MessageId
     */
    public function messageId();

    /**
     * @param MessageId $messageId
     */
    public function setMessageId(MessageId $messageId);
}
