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
use Cubiche\Domain\EventSourcing\EventStore\EventStoreInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Metadata\ClassMetadata;
use Cubiche\Domain\EventSourcing\Migrations\Generator\MigrationGenerator;
use Cubiche\Domain\EventSourcing\Migrations\Manager\MigrationManager;
use Cubiche\Domain\EventSourcing\Migrations\Store\MigrationStoreInterface;
use Cubiche\Domain\EventSourcing\Utils\NameResolver;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\EventSourcing\Versioning\VersionManager;

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
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * MigrationGenerator constructor.
     *
     * @param ClassMetadataFactory    $metadataFactory
     * @param MigrationStoreInterface $migrationStore
     * @param EventStoreInterface     $eventStore
     * @param string                  $migrationsDirectory
     */
    public function __construct(
        ClassMetadataFactory $metadataFactory,
        MigrationStoreInterface $migrationStore,
        EventStoreInterface $eventStore,
        $migrationsDirectory
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->migrationStore = $migrationStore;
        $this->eventStore = $eventStore;
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
        $numExecutedMigrations = $this->migrationManager()->numberOfMigrations();

        // available migration count
        $availableMigrations = $this->migrationManager->availableVersions();
        $numAvailableMigrations = count($availableMigrations);

        // new migration count
        $numNewMigrations = $numAvailableMigrations - $numExecutedMigrations;

        return new Status(
            $this->migrationManager->latestAvailableVersion(),
            $this->migrationManager->nextAvailableVersion(),
            $this->migrationManager->latestMigration(),
            $numExecutedMigrations,
            $numAvailableMigrations,
            $numNewMigrations
        );
    }

    /**
     * @return bool
     */
    public function migrate()
    {
        $nextMigration = $this->migrationManager()->nextMigrationToExecute();
        $currentApplicationVersion = VersionManager::currentApplicationVersion();

        if ($nextMigration !== null) {
            foreach ($nextMigration->aggregates() as $aggregateMigrationClass) {
                // -- start current application context --
                /** @var MigrationInterface $migrationClass */
                $migrationClass = new $aggregateMigrationClass();

                $aggregateClassName = $migrationClass->aggregateClassName();
                $currentAggregateVersion = VersionManager::versionOfClass(
                    $aggregateClassName,
                    $currentApplicationVersion
                );

                // get all event streams for this aggregate class name
                $eventStreams = $this->eventStore->loadAll(
                    $this->streamName($aggregateClassName),
                    $currentAggregateVersion,
                    $currentApplicationVersion
                );
                // -- end current application context --

                // -- start new version context --
                $nextApplicationVersion = $nextMigration->version();

                // iterate for every aggregateRoot event stream
                foreach ($eventStreams as $aggregateRootEventStream) {
                    // migrate the current aggregate event stream
                    $newAggregateRootEventStream = $migrationClass->migrate($aggregateRootEventStream);

                    if ($newAggregateRootEventStream === null ||
                        ($newAggregateRootEventStream !== null && !$newAggregateRootEventStream instanceof EventStream)
                    ) {
                        throw new \RuntimeException(sprintf(
                            'Invalid migration class %s. The migration method should return the new EventStream',
                            $aggregateMigrationClass
                        ));
                    }

                    // calculate the new version for every event
                    $pathVersion = 0;
                    foreach ($newAggregateRootEventStream->events() as $event) {
                        $event->setVersion(++$pathVersion);
                    }

                    // and the new version for this aggregateRoot
                    $newAggregateRootVersion = Version::fromString($nextMigration->version()->__toString());
                    $newAggregateRootVersion->setPatch($pathVersion);

                    // persist the new event stream for this aggregateRoot.
                    $this->migrateAggregateRoot(
                        $aggregateClassName,
                        $newAggregateRootEventStream,
                        $newAggregateRootVersion,
                        $nextApplicationVersion
                    );
                }

                // persist the new version of this aggregate class in the VersionManager
                VersionManager::persistVersionOfClass(
                    $aggregateClassName,
                    $nextMigration->version(),
                    $nextApplicationVersion
                );
                // -- end new version context --
            }

            // persist the new migration in the store
            $this->migrationManager->persistMigration($nextMigration);

            return true;
        }

        return false;
    }

    /**
     * @param string      $aggregateClassName
     * @param EventStream $eventStream
     * @param Version     $aggregateVersion
     * @param Version     $applicationVersion
     */
    protected function migrateAggregateRoot(
        $aggregateClassName,
        EventStream $eventStream,
        Version $aggregateVersion,
        Version $applicationVersion
    ) {
        // this will persist the eventstream in a new flow, because the
        // currentApplicationVersion is set to the target migration
        $this->eventStore->persist($eventStream, $aggregateVersion, $applicationVersion);
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

    /**
     * @param string $aggregateClassName
     *
     * @return string
     */
    protected function streamName($aggregateClassName)
    {
        return NameResolver::resolve($aggregateClassName);
    }
}
