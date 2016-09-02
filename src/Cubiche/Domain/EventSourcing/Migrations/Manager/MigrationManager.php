<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Manager;

use Cubiche\Domain\EventSourcing\Migrations\Migration;
use Cubiche\Domain\EventSourcing\Migrations\Store\MigrationStoreInterface;
use Cubiche\Domain\EventSourcing\Versioning\Version;
use Cubiche\Tests\Generator\ClassUtils;

/**
 * MigrationManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationManager
{
    /**
     * @var MigrationStoreInterface
     */
    protected $migrationStore;

    /**
     * @var string
     */
    protected $migrationsDirectory;

    /**
     * @var Migration[]
     */
    protected $migrationsInFile = array();

    /**
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * MigrationManager constructor.
     *
     * @param MigrationStoreInterface $migrationStore
     * @param string                  $migrationsDirectory
     */
    public function __construct(MigrationStoreInterface $migrationStore, $migrationsDirectory)
    {
        $this->migrationStore = $migrationStore;
        $this->migrationsDirectory = $migrationsDirectory;
    }

    /**
     * Returns the last migrated version from the migration store.
     *
     * @return Migration
     */
    public function currentMigration()
    {
        return $this->migrationStore->getLast();
    }

    /**
     * Returns the latest available migration version.
     *
     * @return Version|null
     */
    public function latestVersion()
    {
        $availableVersions = array_keys($this->migrationsInFile);
        $latest = end($availableVersions);

        return $latest !== false ? Version::fromString($latest) : null;
    }

    /**
     * Returns an array of available migration version numbers.
     *
     * @return Version[]
     */
    public function availableVersions()
    {
        $availableVersions = array();
        /** @var Migration $migration */
        foreach ($this->migrationsInFile as $key => $migration) {
            $availableVersions[] = $migration->version();
        }

        return $availableVersions;
    }

    /**
     * Returns all migrated versions from the migration store.
     *
     * @return Version[]
     */
    public function migratedVersions()
    {
        $migratedVersions = array();
        foreach ($this->migrationStore->findAll() as $migration) {
            $migratedVersions[] = $migration->version();
        };

        return $migratedVersions;
    }

    /**
     * @return int
     */
    public function numberOfMigratedVersions()
    {
        return $this->migrationStore->count();
    }

    /**
     * Check if a version has been migrated or not yet.
     *
     * @param Version $version
     *
     * @return bool
     */
    public function hasMigratedVersion(Version $version)
    {
        return $this->migrationStore->hasVersion($version);
    }

    /**
     * Returns the array of migrations to executed based on the given target version number.
     *
     * @param Version $to
     *
     * @return Migration[]
     */
    public function migrationsToExecute(Version $to)
    {
        $migratedVersions = array();
        foreach ($this->migratedVersions() as $migratedVersion) {
            $migratedVersions[] = $migratedVersion->__toString();
        }

        $migrationsToExecute = array();
        foreach ($this->availableVersions() as $version) {
            if (!in_array($version->__toString(), $migratedVersions) && $version->compareTo($to) >= 0) {
                $migrationsToExecute[] = $this->migrationsInFile[$version->__toString()];
            }
        }

        return $migrationsToExecute;
    }

    /**
     * @param array   $aggregates
     * @param Version $version
     *
     * @throws DuplicateVersionException
     */
    public function registerMigration(array $aggregates, Version $version)
    {
        if (isset($this->migrationsInFile[$version->__toString()])) {
            throw new \RuntimeException(sprintf(
                'Migration version %s already registered.',
                $version->__toString()
            ));
        }

        $this->migrationsInFile[$version->__toString()] = new Migration($aggregates, $version, new \DateTime());

        uksort($this->migrationsInFile, function ($v1, $v2) {
            return Version::fromString($v1)->compareTo(Version::fromString($v2));
        });
    }

    /**
     * Register migrations from a given directory. Recursively finds all files and registers
     * them as migrations.
     */
    public function registerMigrationsFromDirectory()
    {
        $path = realpath($this->migrationsDirectory);
        if ($path === false) {
            throw new \RuntimeException(sprintf(
                'Invalid migration directory %s',
                $this->migrationsDirectory
            ));
        }

        $iterator = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            ),
            '/^.+'.preg_quote($this->fileExtension).'$/i',
            \RecursiveRegexIterator::GET_MATCH
        );

        $migrations = array();
        foreach ($iterator as $file) {
            $sourceFile = $file[0];

            if (!preg_match('(^phar:)i', $sourceFile)) {
                $sourceFile = realpath($sourceFile);
            }

            // extract the version number from the front of the directory name
            $relativeSourceFile = str_replace($path.'/', '', $sourceFile);
            $pieces = explode('/', $relativeSourceFile);
            $version = reset($pieces);

            if (!isset($migrations[$version])) {
                $migrations[$version] = array();
            }

            $className = $this->getClassName($sourceFile);
            if ($className !== null) {
                $migrations[$version][] = $className;
            } else {
                throw new \UnexpectedValueException(sprintf(
                    'There is no class migration in %s file.',
                    $sourceFile
                ));
            }
        }

        foreach ($migrations as $versionNumber => $aggregates) {
            $version = $this->directoryToVersion($versionNumber);

            if ($version == Version::fromString('0.0.0')) {
                throw new \RuntimeException(sprintf(
                    'Invalid migration directory %s.',
                    $versionNumber
                ));
            }

            $this->registerMigration($aggregates, $version);
        }
    }

    /**
     * @param $directory
     *
     * @return Version
     */
    private function directoryToVersion($directory)
    {
        $versionNumber = str_replace(array('V', '_'), array('', '.'), $directory);

        return Version::fromString($versionNumber);
    }

    /**
     * @param string $sourceFile
     *
     * @return string
     */
    private function getClassName($sourceFile)
    {
        require_once $sourceFile;

        $classes = ClassUtils::getClassesInFile($sourceFile);
        if (count($classes) > 0) {
            return $classes[0];
        }

        return;
    }
}
