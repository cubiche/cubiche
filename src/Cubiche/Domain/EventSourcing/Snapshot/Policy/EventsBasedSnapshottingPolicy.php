<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Snapshot\Policy;

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;

/**
 * EventsBasedSnapshottingPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventsBasedSnapshottingPolicy implements SnapshottingPolicyInterface
{
    /**
     * {@inheritdoc}
     */
    public function shouldCreateSnapshot(EventSourcedAggregateRootInterface $eventSourcedAggregateRoot)
    {
        $recordedEvents = $eventSourcedAggregateRoot->recordedEvents();

        return count($recordedEvents) > 0;
    }
}
