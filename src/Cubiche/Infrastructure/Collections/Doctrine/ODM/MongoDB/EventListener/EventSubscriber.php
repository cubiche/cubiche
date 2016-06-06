<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber as BaseEventSubscriber;

/**
 * EventSubscriber class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EventSubscriber extends EventListener implements BaseEventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::PRE_PERSIST,
            Events::POST_PERSIST,
            Events::POST_LOAD,
            Events::POST_LOAD_CLASS_METADATA,
            Events::REGISTER_DRIVER_METADATA,
        );
    }
}
