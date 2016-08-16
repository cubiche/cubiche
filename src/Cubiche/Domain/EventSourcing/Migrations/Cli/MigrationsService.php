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
use Cubiche\Domain\EventSourcing\Migrations\Generator\MigrationGenerator;
use Cubiche\Domain\EventSourcing\Versioning\VersionIncrementType;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;
use Cubiche\Tests\Generator\ClassUtils;

/**
 * MigrationsService class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationsService
{
    /**
     * @var MigrationGenerator
     */
    protected $generator;

    /**
     * MigrationsService constructor.
     *
     * @param MigrationGenerator $generator
     */
    public function __construct(MigrationGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param MigrationsGenerateCommand $command
     */
    public function migrationsGenerate(MigrationsGenerateCommand $command)
    {
        if ($command->version() !== null) {
        } elseif ($command->aggregate()) {
            $aggregateClassName = $this->getClassName($command->aggregate());
            if (!class_exists($aggregateClassName)) {
                $command->getIo()->writeLine(
                    '<error>Invalid class name '.$aggregateClassName.'.</error>'
                );
            } else {
                $version = VersionManager::versionOfClass($aggregateClassName);
                $version->increment(VersionIncrementType::MINOR());

                $command->getIo()->writeLine(
                    'Generating migration <c2>'.$version->__toString().'</c2> for <c2>'.$aggregateClassName.'</c2>'
                );

                try {
                    $this->generator->generate($aggregateClassName, $version, VersionIncrementType::MINOR());

                    $command->getIo()->writeLine(
                        'The migration file has been <c1>successfully generated</c1>'
                    );
                } catch (\Exception $e) {
                    $command->getIo()->writeLine(
                        '<error>'.$e->getMessage().'</error>'
                    );
                }
            }
        } else {
            $command->getIo()->writeLine(
                '<error>A version number or an aggregate class name is needed.</error>'
            );
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
        $command->getIo()->writeLine('status ok');
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
