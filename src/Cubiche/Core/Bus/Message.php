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
    protected $id;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->initIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Set the message identifier.
     */
    protected function initIdentifier()
    {
        if ($this->id === null) {
            $this->id = MessageId::next();
        }
    }
}
