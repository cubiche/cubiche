<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Event;

/**
 * PostPersistEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostPersistEvent extends LifecycleEvent
{
    const eventName = 'aggregate.post_persist';

    /**
     * {@inheritdoc}
     */
    public function messageName(): string
    {
        return self::eventName;
    }
}
