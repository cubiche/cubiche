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

use Cubiche\Domain\EventSourcing\Event\PostRemoveEvent;

/**
 * PostRemoveListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostRemoveListener
{
    /**
     * @param PostRemoveEvent $event
     */
    public function onPostRemove(PostRemoveEvent $event)
    {
        $event->aggregate()->version()->setPatch(
            $event->aggregate()->version()->patch() / 2
        );
    }
}
