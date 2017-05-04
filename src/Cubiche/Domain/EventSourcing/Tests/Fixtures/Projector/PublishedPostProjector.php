<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\Projector;

use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Projector\Projector;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostEventSourced;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\ReadModel\PublishedPost;

/**
 * PublishedPostProjector class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PublishedPostProjector extends Projector
{
    /**
     * @param PostWasCreated $event
     *
     * @return PublishedPost
     */
    public function projectPostWasCreated(PostWasCreated $event)
    {
        return new PublishedPost(
            $event->aggregateId(),
            $event->title()
        );
    }

    /**
     * @param PostTitleWasChanged $event
     * @param PublishedPost       $readModel
     */
    public function projectPostTitleWasChanged(PostTitleWasChanged $event, PublishedPost $readModel)
    {
        $readModel->setTitle($event->title());
    }

    /**
     * @param EventStream $eventStream
     *
     * @return bool
     */
    protected function shouldBeProjected(EventStream $eventStream)
    {
        $events = $eventStream->events();
        foreach ($events as $event) {
            if ($event instanceof PostWasPublished) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param EventStream $eventStream
     *
     * @return bool
     */
    protected function shouldBeRemoved(EventStream $eventStream)
    {
        $events = $eventStream->events();
        foreach ($events as $event) {
            if ($event instanceof PostWasUnPublished) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    protected function writeModelClass()
    {
        return PostEventSourced::class;
    }
}
