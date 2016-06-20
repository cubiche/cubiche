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

use Cubiche\Core\Console\Tests\Fixtures\Post;

/**
 * PostService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostService
{
    /**
     * @param CreatePostCommand $command
     */
    public function createPost(CreatePostCommand $command)
    {
        Post::create($command->title(), $command->content());
    }

    /**
     * @param ChangePostTitleCommand $command
     */
    public function changePostTitle(ChangePostTitleCommand $command)
    {
        // fake post
        $post = Post::create('fake', 'post');
        $post->changeTitle($command->title());
    }
}
