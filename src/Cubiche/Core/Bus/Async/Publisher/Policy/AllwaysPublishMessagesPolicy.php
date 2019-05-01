<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Publisher\Policy;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Serializer\Event\PostDeserializeEvent;
use Cubiche\Core\Serializer\Event\PostSerializeEvent;
use Cubiche\Core\Serializer\Event\PreDeserializeEvent;
use Cubiche\Core\Serializer\Event\PreSerializeEvent;
use Cubiche\Domain\EventSourcing\Event\PostPersistEvent;
use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;

/**
 * AllwaysPublishMessagesPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AllwaysPublishMessagesPolicy implements PublishPolicyInterface
{
    /**
     * @var array
     */
    private $excepts = [
        PreSerializeEvent::eventName,
        PreDeserializeEvent::eventName,
        PostSerializeEvent::eventName,
        PostDeserializeEvent::eventName,
        PrePersistEvent::eventName,
        PostPersistEvent::eventName,
    ];

    /**
     * {@inheritdoc}
     */
    public function shouldPublishMessage(MessageInterface $message)
    {
        if (in_array($message->messageName(), $this->excepts)) {
            return false;
        }

        return true;
    }
}
