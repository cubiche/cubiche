<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\Listener;

use Cubiche\Domain\EventSourcing\DomainEventSubscriberInterface;
use Cubiche\Domain\EventSourcing\Event\PostRemoveEvent;

/**
 * PostRemoveSubscriber class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostRemoveSubscriber extends PostRemoveListener implements DomainEventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            PostRemoveEvent::eventName => 'onPostRemove',
        );
    }
}
