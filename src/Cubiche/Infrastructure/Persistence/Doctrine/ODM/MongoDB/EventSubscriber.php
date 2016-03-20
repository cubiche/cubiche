<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB;

use Cubiche\Infrastructure\Model\Persistence\Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber as BaseEventSubscriber;

/**
 * Event Listener Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EventSubscriber extends EventListener implements BaseEventSubscriber
{
    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::PRE_PERSIST,
            Events::POST_PERSIST,
            Events::PRE_UPDATE,
            Events::POST_UPDATE,
            Events::PRE_LOAD,
            Events::POST_LOAD,
            Events::PRE_REMOVE,
            Events::POST_REMOVE,
            Events::PRE_FLUSH,
            Events::ON_FLUSH,
            Events::POST_FLUSH,
            Events::LOAD_CLASS_METADATA,
        );
    }
}
