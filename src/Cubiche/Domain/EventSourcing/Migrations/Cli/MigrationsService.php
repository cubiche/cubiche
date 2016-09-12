<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Cli;

use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsGenerateCommand;
use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsMigrateCommand;
use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsStatusCommand;
use Cubiche\Domain\EventSourcing\Migrations\Migrator;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\EventSourcing\Versioning\VersionIncrementType;
use Webmozart\Console\UI\Component\Table;
use Webmozart\Console\UI\Style\TableStyle;

/**
 * MigrationsService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationsService
{
    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * MigrationsService constructor.
     *
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
    }

    /**
     * @param MigrationsGenerateCommand $command
     */
    public function migrationsGenerate(MigrationsGenerateCommand $command)
    {
        $latestAvailableVersion = $this->migrator->status()->latestAvailableVersion();
        if ($latestAvailableVersion == null) {
            $latestAvailableVersion = Version::fromString('0.0.0');
        }

        $nextVersion = $latestAvailableVersion;
        if ($command->isMajor()) {
            $nextVersion->increment(VersionIncrementType::MAJOR());
        } else {
            $nextVersion->increment(VersionIncrementType::MINOR());
        }

        $command->getIo()->writeLine(
            'Generating migrations classes for version <c2>'.$nextVersion->__toString().'</c2>'
        );

        try {
            $this->migrator->generate($nextVersion);

            $command->getIo()->writeLine(
                'The migration has been <c1>successfully generated</c1>'
            );
        } catch (\Exception $e) {
            $command->getIo()->writeLine(
                '<error>'.$e->getMessage().'</error>'
            );
        }
    }

    /**
     * @param MigrationsMigrateCommand $command
     */
    public function migrationsMigrate(MigrationsMigrateCommand $command)
    {
        try {
            $status = $this->migrator->status();
            $nextAvailableVersion = $status->nextAvailableVersion();

            if ($nextAvailableVersion !== null) {
                $command->getIo()->writeLine(
                    'Starting migration to version <c2>'.$nextAvailableVersion->__toString().'</c2>'
                );

                $this->migrator->migrate();

                $command->getIo()->writeLine(
                    'The migration has been <c1>successfully executed</c1>'
                );
            } else {
                $command->getIo()->writeLine('<warn>There is no migration to execute.</warn>');
            }
        } catch (\Exception $e) {
            $command->getIo()->writeLine(
                '<error>'.$e->getMessage().'</error>'
            );
        }
    }

    /**
     * @param MigrationsStatusCommand $command
     */
    public function migrationsStatus(MigrationsStatusCommand $command)
    {
        try {
            $status = $this->migrator->status();
            $latestMigration = $status->latestMigration();

            $rows = array(
                array(
                    ' Current Version',
                    $latestMigration ? sprintf(
                        '<c2>%s (%s)</c2>',
                        $latestMigration->version()->__toString(),
                        $latestMigration->createdAt()->format('Y-m-d H:i:s')
                    ) : '<c2>0</c2>',
                ),
                array(
                    ' Latest Version',
                    $status->latestAvailableVersion() ?
                        '<c2>'.$status->latestAvailableVersion()->__toString().'</c2>' :
                        '<c2>none</c2>',
                ),
                array(
                    ' Next Version',
                    $status->nextAvailableVersion() ?
                        '<c2>'.$status->nextAvailableVersion()->__toString().'</c2>' :
                        '<c2>none</c2>',
                ),
                array(
                    ' Executed Migrations',
                    '<c2>'.$status->numExecutedMigrations().'</c2>',
                ),
                array(
                    ' Available Migrations',
                    '<c2>'.$status->numAvailableMigrations().'</c2>',
                ),
                array(
                    ' New Migrations',
                    '<c2>'.$status->numNewMigrations().'</c2>',
                ),
            );

            $table = new Table(TableStyle::borderless());
            foreach ($rows as $row) {
                $table->addRow($row);
            }

            $table->render($command->getIo());
        } catch (\Exception $e) {
            $command->getIo()->writeLine(
                '<error>'.$e->getMessage().'</error>'
            );
        }
    }
}
