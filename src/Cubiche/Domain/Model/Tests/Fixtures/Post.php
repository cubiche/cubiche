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

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Domain\Identity\UUID;
use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\Model\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\System\StringLiteral;

/**
 * Post class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Post extends AggregateRoot
{
    /**
     * @var StringLiteral
     */
    protected $title;

    /**
     * @var StringLiteral
     */
    protected $content;

    /**
     * @var bool
     */
    protected $published = false;

    /**
     * @var ArrayCollection
     */
    protected $categories;

    /**
     * Post constructor.
     *
     * @param IdInterface $id
     */
    protected function __construct(IdInterface $id)
    {
        parent::__construct($id);

        $this->categories = new ArrayCollection();
    }

    /**
     * @param StringLiteral $title
     * @param StringLiteral $content
     *
     * @return Post
     */
    public static function create(StringLiteral $title, StringLiteral $content)
    {
        $post = new self(UUID::next());
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
