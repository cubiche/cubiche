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
     * Post constructor.
     *
     * @param PostId $id
     * @param string $title
     * @param string $content
     */
    public function __construct(PostId $id, $title, $content)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->content = $content;
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
        $this->title = $newTitle;
    }

    /**
     * Publish a post.
     */
    public function publish()
    {
        $this->published = true;
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
