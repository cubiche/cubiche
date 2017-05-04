<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures;

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRoot;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\Metadata\Annotations as ES;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasRemoved;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;
use Cubiche\Domain\Model\Tests\Fixtures\Post;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * PostEventSourced class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @ES\Migratable
 */
class PostEventSourced extends Post implements EventSourcedAggregateRootInterface
{
    use EventSourcedAggregateRoot;

    /**
     * Post constructor.
     *
     * @param PostId $id
     * @param string $title
     * @param string $content
     */
    public function __construct(PostId $id, $title, $content)
    {
        $this->id = $id;

        $this->recordApplyAndPublishEvent(
            new PostWasCreated($this->id(), $title, $content)
        );
    }

    /**
     * @param $newTitle
     */
    public function changeTitle($newTitle)
    {
        $this->recordApplyAndPublishEvent(
            new PostTitleWasChanged($this->id, $newTitle)
        );
    }

    /**
     * Publish a post.
     */
    public function publish()
    {
        $this->recordApplyAndPublishEvent(
            new PostWasPublished($this->id())
        );
    }

    /**
     * Unpublish a post.
     */
    public function unpublish()
    {
        $this->recordApplyAndPublishEvent(
            new PostWasUnPublished($this->id())
        );
    }

    /**
     * Publish a post.
     */
    public function remove()
    {
        $this->recordApplyAndPublishEvent(
            new PostWasRemoved($this->id())
        );
    }

    /**
     * @param PostWasCreated $event
     */
    protected function applyPostWasCreated(PostWasCreated $event)
    {
        $this->title = $event->title();
        $this->content = $event->content();
    }

    /**
     * @param PostTitleWasChanged $event
     */
    protected function applyPostTitleWasChanged(PostTitleWasChanged $event)
    {
        $this->title = $event->title();
    }

    /**
     * @param PostWasPublished $event
     */
    protected function applyPostWasPublished(PostWasPublished $event)
    {
        $this->published = true;
    }

    /**
     * @param PostWasUnPublished $event
     */
    protected function applyPostWasUnPublished(PostWasUnPublished $event)
    {
        $this->published = false;
    }
}
