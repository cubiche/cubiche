<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Bus\Async\Publisher;

use Cubiche\Core\Bus\Async\Publisher\MessagePublisherInterface;
use Cubiche\Core\Bus\Async\Serializer\Envelope;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Serializer\SerializerInterface;

/**
 * RabbitMQMessagePublisher class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RabbitMQMessagePublisher implements  MessagePublisherInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * RabbitMQMessagePublisher constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param MessageInterface $message
     */
    public function publishMessage(MessageInterface $message)
    {
        $envelope = new Envelope($message);
        $envelope->setMessageName($message->messageName());
        $serialized = $this->serializer->serialize($envelope);
        // send the message to the queue
    }
}
