#!/usr/bin/env php
<?php

use Cubiche\Core\EventDispatcher\EventInterface;
use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Bus\Command\CommandBus;
use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Identity\UUID;
use Cubiche\Domain\Model\EventSourcing\EntityDomainEvent;
use Cubiche\Core\Console\ConsoleApplication;
use Cubiche\Core\Console\Config\DefaultApplicationConfig;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\IO\IO;

if (file_exists($autoload = __DIR__.'/../../../autoload.php')) {
    require_once $autoload;
} else {
    require_once __DIR__.'/../../../../vendor/autoload.php';
}

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

/**
 * BlogId class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogId extends UUID
{
}


/**
 * BlogWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogWasCreated extends EntityDomainEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * BlogWasCreated constructor.
     *
     * @param BlogId $id
     * @param string $name
     */
    public function __construct(BlogId $id, $name)
    {
        parent::__construct($id);

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}

/**
 * CreateBlogCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CreateBlogCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * CreateBlogCommand constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}

/**
 * BlogHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogHandler
{
    public function createBlog(CreateBlogCommand $command)
    {
        Blog::create($command->name());
    }
}

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

/**
 * PostId class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostId extends UUID
{
}


/**
 * PostWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasCreated extends EntityDomainEvent
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
     * PostWasCreated constructor.
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
     * @return PostId
     */
    public function id()
    {
        return $this->aggregateId();
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
}

/**
 * PostTitleWasChanged class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostTitleWasChanged extends EntityDomainEvent
{
    /**
     * @var string
     */
    protected $title;

    /**
     * PostTitleWasChanged constructor.
     *
     * @param PostId $id
     * @param string $title
     */
    public function __construct(PostId $id, $title)
    {
        parent::__construct($id);

        $this->title = $title;
    }

    /**
     * @return PostId
     */
    public function id()
    {
        return $this->aggregateId();
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }
}

/**
 * PostWasPublished class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasPublished extends EntityDomainEvent
{
}

/**
 * CreatePostCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CreatePostCommand implements CommandInterface
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
     * CreatePostCommand constructor.
     *
     * @param string $title
     * @param string $content
     */
    public function __construct($title, $content = null)
    {
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}

/**
 * ChangePostTitleCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChangePostTitleCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * ChangePostTitleCommand constructor.
     *
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}

/**
 * PostService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostService
{
    public function createPost(CreatePostCommand $command)
    {
        Post::create($command->title(), $command->content());
    }

    public function changePostTitle(ChangePostTitleCommand $command)
    {
        // fake post
        $post = Post::create('fake', 'post');
        $post->changeTitle($command->title());
    }
}

$commandBus = CommandBus::create();

$postService = new PostService();
$commandBus->addHandler(CreateBlogCommand::class, new BlogHandler());
$commandBus->addHandler(CreatePostCommand::class, $postService);
$commandBus->addHandler(ChangePostTitleCommand::class, $postService);

/**
 * SampleApplicationConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SampleApplicationConfig extends DefaultApplicationConfig
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('test')
            ->setVersion('1.0.0')
            ->beginCommand(CreateBlogCommand::class)
                ->setDescription('Create a new blog')
                ->addAlias('createBlog')
                ->addArgument('name', Argument::REQUIRED | Argument::STRING, 'The blog name')
            ->end()
            ->beginCommand('posts')
                ->setDescription('Manage posts')
                ->beginSubCommand(CreatePostCommand::class)
                    ->setDescription('Create a new post')
                    ->addAlias('create')
                    ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The post title')
                    ->addArgument('content', Argument::OPTIONAL, 'The post content')
                    ->onPreDispatch(function(EventInterface $event, IO $io) {
                        $io->writeLine($event->eventName());
                    })
                ->end()
                ->beginSubCommand(ChangePostTitleCommand::class)
                    ->setDescription('Change the post title')
                    ->addAlias('change')
                    ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The new post title')
                    ->onPostDispatch(function(EventInterface $event, IO $io) {
                        $io->writeLine($event->eventName());
                    })
                ->end()
            ->end()
        ;
    }
}

$cli = new ConsoleApplication(new SampleApplicationConfig(), $commandBus);
$cli->run();