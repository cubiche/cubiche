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

use Cubiche\Core\Console\Tests\Fixtures\Blog;

/**
 * BlogService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogService
{
    /**
     * @param CreateBlogCommand $command
     */
    public function createBlog(CreateBlogCommand $command)
    {
        Blog::create($command->name());
    }
}
