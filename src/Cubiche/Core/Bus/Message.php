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
 * Message class.
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
     * @var string
     */
    protected $messageName;

    /**
     * Message constructor.
     *
     * @param string|null $messageId
     * @param string|null $messageName
     */
    public function __construct(string $messageId = null, string $messageName = null)
    {
        $this->setMessageId($messageId ? MessageId::fromNative($messageId) : MessageId::next());
        $this->setMessageName($messageName);
    }

    /**
     * {@inheritdoc}
     */
    public function messageId(): MessageId
    {
        return $this->messageId;
    }

    /**
     * @param MessageId|null $messageId
     */
    protected function setMessageId(MessageId $messageId = null)
    {
        $this->messageId = $messageId;
    }

    /**
     * {@inheritdoc}
     */
    public function messageName(): string
    {
        return $this->messageName ? $this->messageName : get_class($this);
    }

    /**
     * @param string|null $messageName
     */
    protected function setMessageName(string $messageName = null)
    {
        $this->messageName = $messageName;
    }
}
