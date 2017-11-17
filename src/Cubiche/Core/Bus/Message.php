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

use Cubiche\Core\Serializer\ReflectionSerializer;

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

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $serializer = new ReflectionSerializer();
        $this->initIdentifier();

        return $serializer->serialize($this);
    }

    /**
     * @param array $data
     *
     * @return MessageInterface
     */
    public static function deserialize(array $data)
    {
        $serializer = new ReflectionSerializer();

        return $serializer->deserialize($data);
    }
}
