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
use Cubiche\Tests\Generator\ClassUtils;
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
        $version = Version::fromString($command->version());

        if (!$version->isMajorVersion() && !$version->isMinorVersion()) {
            $command->getIo()->writeLine(
                '<error>A version number must be a minor (x.x.0) or a major (x.0.0) version.</error>'
            );
        } else {
            $command->getIo()->writeLine(
                'Generating project migration to version <c2>'.$version->__toString().'</c2>'
            );

            try {
                $this->migrator->generate($version);

                $command->getIo()->writeLine(
                    'The migration has been <c1>successfully generated</c1>'
                );
            } catch (\Exception $e) {
                $command->getIo()->writeLine(
                    '<error>'.$e->getMessage().'</error>'
                );
            }
        }
    }

    /**
     * @param MigrationsMigrateCommand $command
     */
    public function migrationsMigrate(MigrationsMigrateCommand $command)
    {
        $command->getIo()->writeLine('migrate ok');
    }

    /**
     * @param MigrationsStatusCommand $command
     */
    public function migrationsStatus(MigrationsStatusCommand $command)
    {
        try {
            $status = $this->migrator->status();
            $currentMigration = $status->currentMigration();

            $rows = array(
                array(
                    ' Current Version',
                    $currentMigration ? sprintf(
                        '<c2>%s (%s)</c2>',
                        $currentMigration->version()->__toString(),
                        $currentMigration->createdAt()->format('Y-m-d H:i:s')
                    ) : '<c2>0</c2>',
                ),
                array(
                    ' Latest Version',
                    $status->latestVersion() ? '<c2>'.$status->latestVersion()->__toString().'</c2>' : '<c2>none</c2>',
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

    /**
     * @param string $sourceFile
     *
     * @return string
     */
    private function getClassName($sourceFile)
    {
        $aggregateClassName = $sourceFile;
        if (!class_exists($aggregateClassName)) {
            $classes = ClassUtils::getClassesInFile($sourceFile);
            if (count($classes) > 0) {
                return $classes[0];
            }
        }

        return $aggregateClassName;
    }
}
