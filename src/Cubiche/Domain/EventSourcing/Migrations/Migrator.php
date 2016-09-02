<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations;

use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Domain\EventSourcing\Metadata\ClassMetadata;
use Cubiche\Domain\EventSourcing\Migrations\Generator\MigrationGenerator;
use Cubiche\Domain\EventSourcing\Migrations\Manager\MigrationManager;
use Cubiche\Domain\EventSourcing\Migrations\Store\MigrationStoreInterface;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * Migrator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Migrator
{
    /**
     * @var ClassMetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var string
     */
    protected $migrationsDirectory;

    /**
     * @var MigrationStoreInterface
     */
    protected $migrationStore;

    /**
     * @var MigrationGenerator
     */
    protected $migrationGenerator;

    /**
     * @var MigrationManager
     */
    protected $migrationManager;

    /**
     * @var int
     */
    protected $numberOfBelowVersions = 1;

    /**
     * @var int
     */
    protected $numberOfAboveVersions = 1;

    /**
     * MigrationGenerator constructor.
     *
     * @param ClassMetadataFactory    $metadataFactory
     * @param MigrationStoreInterface $migrationStore
     * @param string                  $migrationsDirectory
     */
    public function __construct(
        ClassMetadataFactory $metadataFactory,
        MigrationStoreInterface $migrationStore,
        $migrationsDirectory
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->migrationStore = $migrationStore;
        $this->migrationsDirectory = $migrationsDirectory;

        if (!is_dir($migrationsDirectory)) {
            mkdir($migrationsDirectory);
        }
    }

    /**
     * @param Version $version
     */
    public function generate(Version $version)
    {
        if ($this->migrationGenerator()->existsDirectory($version)) {
            throw new \RuntimeException('A project migration with version '.$version->__toString().' already exists.');
        }

        $metadatas = $this->metadataFactory->getAllMetadata();
        /** @var ClassMetadata $classMetadata */
        foreach ($metadatas as $classMetadata) {
            if ($classMetadata !== null && $classMetadata->isMigratable) {
                $this->migrationGenerator->generate($classMetadata->name, $version);
            }
        }
    }

    /**
     * @return Status
     */
    public function status()
    {
        // executed migration count
        $numExecutedMigrations = $this->migrationManager()->numberOfMigratedVersions();

        // available migration count
        $availableMigrations = $this->migrationManager->availableVersions();
        $numAvailableMigrations = count($availableMigrations);

        // new migration count
        $numNewMigrations = $numAvailableMigrations - $numExecutedMigrations;

        return new Status(
            $this->migrationManager->currentMigration(),
            $this->migrationManager->latestVersion(),
            $numExecutedMigrations,
            $numAvailableMigrations,
            $numNewMigrations
        );
    }

    /**
     * @return MigrationGenerator
     */
    protected function migrationGenerator()
    {
        if ($this->migrationGenerator === null) {
            $this->migrationGenerator = new MigrationGenerator($this->migrationsDirectory);
        }

        return $this->migrationGenerator;
    }

    /**
     * @return MigrationManager
     */
    protected function migrationManager()
    {
        if ($this->migrationManager === null) {
            $this->migrationManager = new MigrationManager($this->migrationStore, $this->migrationsDirectory);
            $this->migrationManager->registerMigrationsFromDirectory();
        }

        return $this->migrationManager;
    }
}
