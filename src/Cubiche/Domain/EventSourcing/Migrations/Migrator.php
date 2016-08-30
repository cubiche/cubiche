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
    protected $migrationGenerator;

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
     * @param Version $version
     */
    public function generate(Version $version)
    {
        if ($this->getMigrationGenerator()->existsDirectory($version)) {
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
     * @return MigrationGenerator
     */
    protected function getMigrationGenerator()
    {
        if ($this->migrationGenerator === null) {
            $this->migrationGenerator = new MigrationGenerator($this->migrationsDirectory);
        }

        return $this->migrationGenerator;
    }
}
