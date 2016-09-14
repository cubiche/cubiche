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
                    ->setDescription('Generate an skeleton migrations classes.')
                    ->addOption(
                        'major',
                        null,
                        Option::REQUIRED_VALUE,
                        'If the major option is equal true, the command will generate a major migration
                        or a minor migration otherwise'
                    )
                ->end()
                ->beginSubCommand('migrations-migrate')
                    ->setClass(MigrationsMigrateCommand::class)
                    ->setDescription('Execute a migration to the latest available version.')
                ->end()
                ->beginSubCommand('migrations-status')
                    ->setClass(MigrationsStatusCommand::class)
                    ->setDescription('View the status of a set of migrations.')
                ->end()
            ->end()
        ;
    }
}
