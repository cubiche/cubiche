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

use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Console\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Core\Console\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Core\Console\Tests\Fixtures\Post;

/**
 * PostService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostService
{
    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * PostService constructor.
     *
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param CreatePostCommand $command
     */
    public function createPost(CreatePostCommand $command)
    {
        $this->eventBus->dispatch(new PostWasCreated($command->title(), $command->content()));
    }

    /**
     * @param ChangePostTitleCommand $command
     */
    public function changePostTitle(ChangePostTitleCommand $command)
    {
        $this->eventBus->dispatch(new PostTitleWasChanged($command->title()));
    }
}
