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
use Cubiche\Domain\EventSourcing\Event\PreRemoveEvent;

/**
 * PreRemoveSubscriber class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PreRemoveSubscriber extends PreRemoveListener implements DomainEventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            PreRemoveEvent::eventName => 'onPreRemove',
        );
    }
}
