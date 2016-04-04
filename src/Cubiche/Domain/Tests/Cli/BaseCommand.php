<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Cli;

use Cubiche\Domain\Tests\Generator\AbstractGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BaseCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BaseCommand extends Command
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->addOption(
                'bootstrap',
                null,
                InputOption::VALUE_REQUIRED,
                'A "bootstrap" PHP file that is run at startup'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force override de file if exists?'
            )
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('bootstrap') && file_exists($input->getOption('bootstrap'))) {
            include $input->getOption('bootstrap');
        }

        $generators = $this->getGenerators($input);
        foreach ($generators as $generator) {
            try {
                $generator->write('', $input->getOption('force'));

                $output->writeln(
                    sprintf(
                        '<info>Generated test for "%s" in "%s".</info>',
                        $generator->getClassName(),
                        $generator->getTargetSourceFile()
                    )
                );
            } catch (\RuntimeException $e) {
                $output->writeln(
                    sprintf(
                        '<error>Failed test generation for "%s". The file "%s" exists.</error>',
                        $generator->getClassName(),
                        $generator->getTargetSourceFile()
                    )
                );

                $output->writeln(
                    '<info>You can force the override file using "--force" option.</info>'
                );
            }
        }
    }

    /**
     * @param InputInterface $input An InputInterface instance
     *
     * @return AbstractGenerator[]
     */
    abstract protected function getGenerators(InputInterface $input);

    /**
     * @return string
     */
    protected function getTestsDirectoryName()
    {
        return 'Tests'.DIRECTORY_SEPARATOR.'Units'.DIRECTORY_SEPARATOR;
    }
}
