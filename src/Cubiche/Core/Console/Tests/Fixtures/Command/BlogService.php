<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Console\Tests\Fixtures\Command;

use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Core\Console\Tests\Fixtures\Blog;
use Cubiche\Core\Console\Tests\Fixtures\Event\BlogWasCreated;

/**
 * BlogService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogService
{
    protected $eventBus;

    /**
     * BlogService constructor.
     *
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param CreateBlogCommand $command
     */
    public function createBlog(CreateBlogCommand $command)
    {
        $this->eventBus->dispatch(new BlogWasCreated($command->name()));
    }
}
