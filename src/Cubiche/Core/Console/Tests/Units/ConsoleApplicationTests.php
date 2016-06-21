<?php

/**
 * This file is part of the Cubiche component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Tests\Units;

use Cubiche\Core\Bus\Command\CommandBus;
use Cubiche\Core\Bus\Event\EventBus;
use Cubiche\Core\Console\Api\Config\ApplicationConfig;
use Cubiche\Core\Console\ConsoleApplication;
use Cubiche\Core\Console\Tests\Fixtures\Command\BlogService;
use Cubiche\Core\Console\Tests\Fixtures\Command\ChangePostTitleCommand;
use Cubiche\Core\Console\Tests\Fixtures\Command\CreateBlogCommand;
use Cubiche\Core\Console\Tests\Fixtures\Command\CreatePostCommand;
use Cubiche\Core\Console\Tests\Fixtures\Command\PostService;
use Cubiche\Core\EventDispatcher\EventInterface;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\IO\Input;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\Api\IO\Output;
use Webmozart\Console\Args\ArgvArgs;
use Webmozart\Console\Handler\Help\HelpHandler;
use Webmozart\Console\IO\InputStream\StringInputStream;
use Webmozart\Console\IO\OutputStream\BufferedOutputStream;

/**
 * ConsoleApplicationTests class.
 *
 * $commandBus = CommandBus::create();
 *
 * $postService = new PostService();
 * $commandBus->addHandler(CreateBlogCommand::class, new BlogService());
 * $commandBus->addHandler(CreatePostCommand::class, $postService);
 * $commandBus->addHandler(ChangePostTitleCommand::class, $postService);
 *
 * class SampleApplicationConfig extends DefaultApplicationConfig
 * {
 *     protected function configure()
 *     {
 *         parent::configure();
 *
 *         $this
 *             ->setName('test')
 *             ->setVersion('1.0.0')
 *             ->beginCommand('blog')
 *                 ->setClass(CreateBlogCommand::class)
 *                 ->setDescription('Create a new blog')
 *                 ->addArgument('name', Argument::REQUIRED | Argument::STRING, 'The blog name')
 *             ->end()
 *             ->beginCommand('post')
 *                 ->setDescription('Manage posts')
 *                 ->beginSubCommand('create')
 *                     ->setClass(CreatePostCommand::class)
 *                     ->setDescription('Create a new post')
 *                     ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The post title')
 *                     ->addArgument('content', Argument::OPTIONAL, 'The post content')
 *                     ->onPreDispatchEvent(function(EventInterface $event, IO $io) {
 *                         $io->writeLine($event->eventName());
 *                     })
 *                 ->end()
 *                 ->beginSubCommand('change')
 *                     ->setClass(ChangePostTitleCommand::class)
 *                     ->setDescription('Change the post title')
 *                     ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The new post title')
 *                     ->onPostDispatchEvent(function(EventInterface $event, IO $io) {
 *                         $io->writeLine($event->eventName());
 *                     })
 *                 ->end()
 *             ->end()
 *         ;
 *     }
 * }
 *
 * $cli = new ConsoleApplication(new SampleApplicationConfig(), $commandBus);
 * $cli->run();
 */
class ConsoleApplicationTests extends TestCase
{
    /**
     * @return ApplicationConfig
     */
    protected function createConfiguration()
    {
        $config = new ApplicationConfig();
        $config->setCatchExceptions(false);
        $config->setTerminateAfterRun(false);
        $config->setIOFactory(function ($application, $args, $inputStream, $outputStream, $errorStream) {
            return new IO(new Input($inputStream), new Output($outputStream), new Output($errorStream));
        });

        return $config;
    }

    /**
     * @param callable|ApplicationConfig $config
     *
     * @return ConsoleApplication
     */
    protected function createApplication($config)
    {
        $commandBus = CommandBus::create();
        $eventBus = DomainEventPublisher::eventBus();

        $postService = new PostService();

        $commandBus->addHandler(CreateBlogCommand::class, new BlogService());
        $commandBus->addHandler(CreatePostCommand::class, $postService);
        $commandBus->addHandler(ChangePostTitleCommand::class, $postService);

        return new ConsoleApplication($config, $commandBus, $eventBus);
    }

    /**
     * Test run help.
     */
    public function testRunHelpCommand()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('help')
                    ->markDefault()
                    ->setHandler(function () {
                        return new HelpHandler();
                    })
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'help')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->constant('<c1>help</c1>')
        ;

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication(function () use ($config) {
                return $config;
            }))
            ->and($args = new ArgvArgs(array('test', 'help')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->constant('<c1>help</c1>')
        ;
    }

    /**
     * Test run help subcommand.
     */
    public function testRunHelpSubCommand()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('post')
                    ->setDescription('Manage posts')
                    ->beginSubCommand('create')
                        ->setClass(CreatePostCommand::class)
                        ->setDescription('Create a new post')
                    ->end()
                    ->beginSubCommand('change')
                        ->setClass(ChangePostTitleCommand::class)
                        ->setDescription('Change the post title')
                    ->end()
                ->end()
                ->beginCommand('help')
                    ->markDefault()
                    ->addArgument('command', Argument::OPTIONAL, 'The command name')
                    ->setHandler(function () {
                        return new HelpHandler();
                    })
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'post')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->constant('<c1>help</c1>')
        ;
    }

    /**
     * Test run command without class.
     */
    public function testRunCommandWithoutClass()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('create-blog')
                    ->setDescription('Create a new blog')
                    // should set ->setClass()
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'create-blog')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->then()
                ->exception(function () use ($application, $args, $input, $output, $errorOutput) {
                    $application->run($args, $input, $output, $errorOutput);
                })
                ->isInstanceOf(\RuntimeException::class)
        ;
    }

    /**
     * Test run command without arguments.
     */
    public function testRunCommandWithoutArguments()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('create-blog')
                    ->setDescription('Create a new blog')
                    ->setClass(CreateBlogCommand::class)
                    // should have ->addArgument('name', Argument::REQUIRED | Argument::STRING, 'The blog name')
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'create-blog')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->then()
                ->exception(function () use ($application, $args, $input, $output, $errorOutput) {
                    $application->run($args, $input, $output, $errorOutput);
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test run command with default handlers.
     */
    public function testRunCommandWithDefaultHanlder()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('create-blog')
                    ->setDescription('Create a new blog')
                    ->setClass(CreateBlogCommand::class)
                    ->addArgument('name', Argument::REQUIRED | Argument::STRING, 'The blog name')
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'create-blog', 'foo')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->contains('blog was created')
                    ->contains('<c1>blog was created</c1> success')
                    ->contains('name')
                    ->contains('aggregateId')
                    ->contains('occurredOn')
        ;
    }

    /**
     * Test run command with custom predispatch handler.
     */
    public function testRunCommandWithCustomPreDispatchHanlder()
    {
        $configCallback = function (ApplicationConfig $config) {
            $config
                ->beginCommand('post')
                    ->setDescription('Manage posts')
                    ->beginSubCommand('create')
                        ->setClass(CreatePostCommand::class)
                        ->setDescription('Create a new post')
                        ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The post title')
                        ->addArgument('content', Argument::OPTIONAL, 'The post content')
                        ->onPreDispatchEvent(function (EventInterface $event, IO $io) {
                            $io->writeLine('on pre dispatch');
                        })
                    ->end()
                    ->beginSubCommand('change')
                        ->setClass(ChangePostTitleCommand::class)
                        ->setDescription('Change the post title')
                        ->addArgument('title', Argument::REQUIRED | Argument::STRING, 'The new post title')
                        ->onPostDispatchEvent(function (EventInterface $event, IO $io) {
                            $io->writeLine('on post dispatch');
                        })
                    ->end()
                ->end()
            ;
        };

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'post', 'create', 'foo', 'bar')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->contains('on pre dispatch')
                    ->contains('<c1>post was created</c1> success')
        ;

        $this
            ->given($config = $this->createConfiguration())
            ->and($configCallback($config))
            ->and($application = $this->createApplication($config))
            ->and($args = new ArgvArgs(array('test', 'post', 'change', 'baz')))
            ->and($input = new StringInputStream(''))
            ->and($output = new BufferedOutputStream())
            ->and($errorOutput = new BufferedOutputStream())
            ->when($application->run($args, $input, $output, $errorOutput))
            ->then()
                ->string($output->fetch())
                    ->contains('post title was changed')
                    ->contains('on post dispatch')
                    ->contains('title')
                    ->contains('content')
                    ->contains('aggregateId')
                    ->contains('occurredOn')
        ;
    }
}
