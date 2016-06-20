<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Tests\Fixtures;

use Cubiche\Core\Console\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Core\Console\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Core\Console\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\Model\AggregateRoot;

/**
 * Post class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Post extends AggregateRoot
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
     * @param string $title
     * @param string $content
     *
     * @return Post
     */
    public static function create($title, $content)
    {
        $post = new self(PostId::next());

        $post->recordApplyAndPublishEvent(
            new PostWasCreated($post->id(), $title, $content)
        );

        return $post;
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
