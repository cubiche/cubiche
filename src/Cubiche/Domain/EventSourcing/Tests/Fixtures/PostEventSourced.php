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

use Cubiche\Domain\EventSourcing\AggregateRoot;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasRemoved;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasUnPublished;

/**
 * PostEventSourced class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostEventSourced extends AggregateRoot
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var bool
     */
    protected $published = false;

    /**
     * Post constructor.
     *
     * @param PostId $id
     * @param string $title
     * @param string $content
     */
    public function __construct(PostId $id, $title, $content)
    {
        parent::__construct($id);

        $this->recordAndApplyEvent(
            new PostWasCreated($this->id(), $title, $content)
        );
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function published()
    {
        return $this->published;
    }

    /**
     * @param $newTitle
     */
    public function changeTitle($newTitle)
    {
        $this->recordAndApplyEvent(
            new PostTitleWasChanged($this->id, $newTitle)
        );
    }

    /**
     * Publish a post.
     */
    public function publish()
    {
        $this->recordAndApplyEvent(
            new PostWasPublished($this->id())
        );
    }

    /**
     * Unpublish a post.
     */
    public function unpublish()
    {
        $this->recordAndApplyEvent(
            new PostWasUnPublished($this->id())
        );
    }

    /**
     * Publish a post.
     */
    public function remove()
    {
        $this->recordAndApplyEvent(
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

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return parent::equals($other) &&
            $this->title() == $other->title() &&
            $this->content() == $other->content() &&
            $this->published() == $other->published()
        ;
    }
}
