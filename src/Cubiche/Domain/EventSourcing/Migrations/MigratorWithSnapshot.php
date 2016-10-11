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
use Cubiche\Domain\EventSourcing\Migrations\Store\MigrationStoreInterface;
use Cubiche\Domain\EventSourcing\Snapshot\Snapshot;
use Cubiche\Domain\EventSourcing\Snapshot\SnapshotStoreInterface;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * MigratorWithSnapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigratorWithSnapshot extends Migrator
{
    /**
     * @var SnapshotStoreInterface
     */
    protected $snapshotStore;

    /**
     * MigrationGenerator constructor.
     *
     * @param ClassMetadataFactory    $metadataFactory
     * @param MigrationStoreInterface $migrationStore
     * @param EventStoreInterface     $eventStore
     * @param SnapshotStoreInterface  $snapshotStore
     * @param string                  $migrationsDirectory
     */
    public function __construct(
        ClassMetadataFactory $metadataFactory,
        MigrationStoreInterface $migrationStore,
        EventStoreInterface $eventStore,
        SnapshotStoreInterface $snapshotStore,
        $migrationsDirectory
    ) {
        parent::__construct($metadataFactory, $migrationStore, $eventStore, $migrationsDirectory);

        $this->snapshotStore = $snapshotStore;
    }

    /**
     * {@inheritdoc}
     */
    protected function migrateAggregateRoot(
        $aggregateClassName,
        EventStream $eventStream,
        Version $aggregateVersion,
        Version $applicationVersion
    ) {
        parent::migrateAggregateRoot($aggregateClassName, $eventStream, $aggregateVersion, $applicationVersion);

        // reconstruct the new aggregate root
        $aggregateRoot = call_user_func(
            array($aggregateClassName, 'loadFromHistory'),
            $eventStream
        );

        // create the snapshot
        $snapshot = new Snapshot($this->streamName($aggregateClassName), $aggregateRoot, new \DateTime());

        // pserist the new snapshot
        $this->snapshotStore->persist($snapshot, $applicationVersion);
    }
}
