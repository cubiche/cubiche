<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventSubscriber as BaseEventSubscriber;

/**
 * MetadataEventSubscriber class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MetadataEventSubscriber extends MetadataEventListener implements BaseEventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::LOAD_CLASS_METADATA,
        );
    }
}
