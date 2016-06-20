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

use Cubiche\Core\Console\Tests\Fixtures\Event\BlogWasCreated;
use Cubiche\Domain\Model\AggregateRoot;

/**
 * Blog class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Blog extends AggregateRoot
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Blog
     */
    public static function create($name)
    {
        $blog = new self(BlogId::next());

        $blog->recordApplyAndPublishEvent(
            new BlogWasCreated($blog->id(), $name)
        );

        return $blog;
    }

    /**
     * @param BlogWasCreated $event
     */
    protected function applyBlogWasCreated(BlogWasCreated $event)
    {
        $this->name = $event->name();
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return parent::equals($other) &&
            $this->name() == $other->name()
        ;
    }
}
