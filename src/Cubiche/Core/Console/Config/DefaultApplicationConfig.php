<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Config;

use Cubiche\Core\Console\Api\Config\ApplicationConfig;
use Webmozart\Console\Api\Application\Application;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;
use Webmozart\Console\Api\Args\RawArgs;
use Webmozart\Console\Api\Event\ConsoleEvents;
use Webmozart\Console\Api\Event\PreHandleEvent;
use Webmozart\Console\Api\Event\PreResolveEvent;
use Webmozart\Console\Api\IO\Input;
use Webmozart\Console\Api\IO\InputStream;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\Api\IO\Output;
use Webmozart\Console\Api\IO\OutputStream;
use Webmozart\Console\Api\Resolver\ResolvedCommand;
use Webmozart\Console\Formatter\AnsiFormatter;
use Webmozart\Console\Formatter\PlainFormatter;
use Webmozart\Console\Handler\Help\HelpHandler;
use Webmozart\Console\IO\ConsoleIO;
use Webmozart\Console\IO\InputStream\StandardInputStream;
use Webmozart\Console\IO\OutputStream\ErrorOutputStream;
use Webmozart\Console\IO\OutputStream\StandardOutputStream;
use Webmozart\Console\UI\Component\NameVersion;

/**
 * DefaultApplicationConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultApplicationConfig extends ApplicationConfig
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setIOFactory(array($this, 'createIO'))
            ->addEventListener(ConsoleEvents::PRE_RESOLVE, array($this, 'resolveHelpCommand'))
            ->addEventListener(ConsoleEvents::PRE_HANDLE, array($this, 'printVersion'))

            ->addOption('help', 'h', Option::NO_VALUE, 'Display help about the command')
            ->addOption('quiet', 'q', Option::NO_VALUE, 'Do not output any message')
            ->addOption(
                'verbose',
                'v',
                Option::OPTIONAL_VALUE,
                'Increase the verbosity of messages: "-v" for normal output, "-vv"
                for more verbose output and "-vvv" for debug',
                null,
                'level'
            )
            ->addOption('version', 'V', Option::NO_VALUE, 'Display this application version')
            ->addOption('ansi', null, Option::NO_VALUE, 'Force ANSI output')
            ->addOption('no-ansi', null, Option::NO_VALUE, 'Disable ANSI output')
            ->addOption('no-interaction', 'n', Option::NO_VALUE, 'Do not ask any interactive question')

            ->beginCommand('help')
                ->markDefault()
                ->setDescription('Display the manual of a command')
                ->addArgument('command', Argument::OPTIONAL, 'The command name')
                ->addOption('man', 'm', Option::NO_VALUE, 'Output the help as man page')
                ->addOption('ascii-doc', null, Option::NO_VALUE, 'Output the help as AsciiDoc document')
                ->addOption('text', 't', Option::NO_VALUE, 'Output the help as plain text')
                ->addOption('xml', 'x', Option::NO_VALUE, 'Output the help as XML')
                ->addOption('json', 'j', Option::NO_VALUE, 'Output the help as JSON')
                ->setHandler(function () {
                    return new HelpHandler();
                })
            ->end()
        ;
    }

    /**
     * @param Application       $application
     * @param RawArgs           $args
     * @param InputStream|null  $inputStream
     * @param OutputStream|null $outputStream
     * @param OutputStream|null $errorStream
     *
     * @return ConsoleIO
     */
    public function createIO(
        Application $application,
        RawArgs $args,
        InputStream $inputStream = null,
        OutputStream $outputStream = null,
        OutputStream $errorStream = null
    ) {
        $inputStream = $inputStream ?: new StandardInputStream();
        $outputStream = $outputStream ?: new StandardOutputStream();
        $errorStream = $errorStream ?: new ErrorOutputStream();
        $styleSet = $application->getConfig()->getStyleSet();

        if ($args->hasToken('--no-ansi')) {
            $outputFormatter = $errorFormatter = new PlainFormatter($styleSet);
        } elseif ($args->hasToken('--ansi')) {
            $outputFormatter = $errorFormatter = new AnsiFormatter($styleSet);
        } else {
            $outputFormatter = $outputStream->supportsAnsi()
                ? new AnsiFormatter($styleSet)
                : new PlainFormatter($styleSet)
            ;

            $errorFormatter = $errorStream->supportsAnsi()
                ? new AnsiFormatter($styleSet)
                : new PlainFormatter($styleSet)
            ;
        }

        $io = new ConsoleIO(
            new Input($inputStream),
            new Output($outputStream, $outputFormatter),
            new Output($errorStream, $errorFormatter)
        );

        if ($args->hasToken('-vvv') || $this->isDebug()) {
            $io->setVerbosity(IO::DEBUG);
        } elseif ($args->hasToken('-vv')) {
            $io->setVerbosity(IO::VERY_VERBOSE);
        } elseif ($args->hasToken('-v')) {
            $io->setVerbosity(IO::VERBOSE);
        }

        if ($args->hasToken('--quiet') || $args->hasToken('-q')) {
            $io->setQuiet(true);
        }

        if ($args->hasToken('--no-interaction') || $args->hasToken('-n')) {
            $io->setInteractive(false);
        }

        return $io;
    }

    /**
     * @param PreResolveEvent $event
     */
    public function resolveHelpCommand(PreResolveEvent $event)
    {
        $args = $event->getRawArgs();

        if ($args->hasToken('-h') || $args->hasToken('--help')) {
            $command = $event->getApplication()->getCommand('help');

            // Enable lenient args parsing
            $parsedArgs = $command->parseArgs($args, true);

            $event->setResolvedCommand(new ResolvedCommand($command, $parsedArgs));
            $event->stopPropagation();
        }
    }

    /**
     * @param PreHandleEvent $event
     */
    public function printVersion(PreHandleEvent $event)
    {
        if ($event->getArgs()->isOptionSet('version')) {
            $version = new NameVersion($event->getCommand()->getApplication()->getConfig());
            $version->render($event->getIO());

            $event->setHandled(true);
        }
    }
}
