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
 * AllwaysPublishMessagesExceptPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AllwaysPublishMessagesExceptPolicy implements PublishPolicyInterface
{
    /**
     * @var array names
     */
    private $names = [
        PreSerializeEvent::eventName,
        PreDeserializeEvent::eventName,
        PostSerializeEvent::eventName,
        PostDeserializeEvent::eventName,
        PrePersistEvent::eventName,
        PostPersistEvent::eventName,
    ];

    /**
     * PublishPredefinedMessagesPolicy constructor.
     *
     * @param array $names
     */
    public function __construct(array $names = [])
    {
        $this->names = array_merge($this->names, $names);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldPublishMessage(MessageInterface $message)
    {
        if (in_array($message->messageName(), $this->names)) {
            return false;
        }

        return true;
    }
}
