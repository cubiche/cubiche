<?php

/**
 * This file is part of the Cubiche component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Cli;

use Cubiche\Core\Console\Config\DefaultApplicationConfig;
use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsGenerateCommand;
use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsMigrateCommand;
use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsStatusCommand;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

/**
 * ApplicationConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ApplicationConfig extends DefaultApplicationConfig
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('migrations')
            ->beginCommand('eventsourcing')
                ->beginSubCommand('migrations-generate')
                    ->setClass(MigrationsGenerateCommand::class)
                    ->setDescription('Generate a blank migration class.')
                    ->addOption(
                        'version',
                        null,
                        Option::REQUIRED_VALUE,
                        'The version to migrate to'
                    )
                    ->addArgument(
                        'aggregate',
                        Argument::OPTIONAL,
                        'The aggregate root class name'
                    )
                ->end()
                ->beginSubCommand('migrations-migrate')
                    ->setClass(MigrationsMigrateCommand::class)
                    ->setDescription('Execute a migration to a specified version or the latest available version.')
                    ->addArgument(
                        'version',
                        Argument::OPTIONAL,
                        'The version to migrate to'
                    )
                ->end()
                ->beginSubCommand('migrations-status')
                    ->setClass(MigrationsStatusCommand::class)
                    ->setDescription('View the status of a set of migrations.')
                    ->addArgument(
                        'showAvailables',
                        Argument::OPTIONAL,
                        'This will display a list of all available migrations and their status'
                    )
                ->end()
            ->end()
        ;
    }
}
