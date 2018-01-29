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
class Message implements MessageInterface
{
    /**
     * @var MessageId
     */
    protected $messageId;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->messageId = MessageId::next();
    }

    /**
     * {@inheritdoc}
     */
    public function messageId()
    {
        return $this->messageId;
    }

    /**
     * @param MessageId $messageId
     */
    public function setMessageId(MessageId $messageId)
    {
        $this->messageId = $messageId;
    }
}
