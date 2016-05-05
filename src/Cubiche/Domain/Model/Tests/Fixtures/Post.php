<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures;

use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Model\Tests\Fixtures\Event\PostWasCreated;

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
     * @var Category[]
     */
    protected $categories = [];

    /**
     * @param string $title
     * @param string $content
     *
     * @return Post
     */
    public static function create($title, $content)
    {
        $post = new self(PostId::fromNative(md5(rand())));
        $post->recordApplyAndPublishEvent(
            new PostWasCreated($post->id(), $title, $content)
        );

        return $post;
    }

    /**
     * @param PostWasCreated $event
     */
    protected function applyPostWasCreated(PostWasCreated $event)
    {
        $this->title = $event->title();
        $this->content = $event->content();
    }
}
