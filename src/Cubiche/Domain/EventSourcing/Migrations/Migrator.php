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
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Domain\EventSourcing\Versioning\VersionIncrementType;

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
     * @var MigrationGenerator
     */
    protected $generator;

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
     * @param ClassMetadataFactory $metadataFactory
     * @param string               $migrationsDirectory
     */
    public function __construct(ClassMetadataFactory $metadataFactory, $migrationsDirectory)
    {
        $this->metadataFactory = $metadataFactory;
        $this->migrationsDirectory = $migrationsDirectory;
    }

    /**
     * @param string  $aggregateClassName
     * @param Version $version
     */
    public function generateClassMigration($aggregateClassName, Version $version)
    {
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $this->metadataFactory->getMetadataForClass($aggregateClassName);
        if ($classMetadata === null || ($classMetadata !== null && !$classMetadata->isMigratable)) {
            throw new \RuntimeException('The class '.$aggregateClassName.' must be migratable.');
        }

        $this->getGenerator()->generate($aggregateClassName, $version, VersionIncrementType::MINOR());
    }

    /**
     * @param Version $version
     */
    public function generateProjectMigration(Version $version)
    {
        if ($this->getGenerator()->existsDirectory($version)) {
            throw new \RuntimeException('A project migration with version '.$version->__toString().' already exists.');
        }

        $metadatas = $this->metadataFactory->getAllMetadata();
        /** @var ClassMetadata $classMetadata */
        foreach ($metadatas as $classMetadata) {
            if ($classMetadata !== null && $classMetadata->isMigratable) {
                $this->getGenerator()->generate($classMetadata->name, $version, VersionIncrementType::MAJOR());
            }
        }
    }

    /**
     * @return MigrationGenerator
     */
    protected function getGenerator()
    {
        if ($this->generator === null) {
            $this->generator = new MigrationGenerator($this->migrationsDirectory);
        }

        return $this->generator;
    }
}
