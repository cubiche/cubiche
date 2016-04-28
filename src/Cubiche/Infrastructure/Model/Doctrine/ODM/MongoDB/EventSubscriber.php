<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB;

use Doctrine\Common\EventSubscriber as BaseEventSubscriber;

/**
 * Event Subscriber Class.
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
            Events::POST_LOAD_CLASS_METADATA,
        );
    }
}
