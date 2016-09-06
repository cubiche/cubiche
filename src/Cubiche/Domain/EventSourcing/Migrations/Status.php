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

use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * Status class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Status
{
    /**
     * @var Migration
     */
    protected $latestMigration;

    /**
     * @var Version
     */
    protected $latestAvailableVersion;

    /**
     * @var int
     */
    protected $numExecutedMigrations;

    /**
     * @var int
     */
    protected $numAvailableMigrations;

    /**
     * @var int
     */
    protected $numNewMigrations;

    /**
     * Status constructor.
     *
     * @param Version   $latestAvailableVersion
     * @param Version   $nextAvailableVersion
     * @param Migration $latestMigration
     * @param int       $numExecutedMigrations
     * @param int       $numAvailableMigrations
     * @param int       $numNewMigrations
     */
    public function __construct(
        Version $latestAvailableVersion = null,
        Version $nextAvailableVersion = null,
        Migration $latestMigration = null,
        $numExecutedMigrations = 0,
        $numAvailableMigrations = 0,
        $numNewMigrations = 0
    ) {
        $this->latestAvailableVersion = $latestAvailableVersion;
        $this->nextAvailableVersion = $nextAvailableVersion;
        $this->latestMigration = $latestMigration;
        $this->numExecutedMigrations = $numExecutedMigrations;
        $this->numAvailableMigrations = $numAvailableMigrations;
        $this->numNewMigrations = $numNewMigrations;
    }

    /**
     * @return Version|null
     */
    public function latestAvailableVersion()
    {
        return $this->latestAvailableVersion;
    }

    /**
     * @return Version|null
     */
    public function nextAvailableVersion()
    {
        return $this->nextAvailableVersion;
    }

    /**
     * @return Migration|null
     */
    public function latestMigration()
    {
        return $this->latestMigration;
    }

    /**
     * @return int
     */
    public function numExecutedMigrations()
    {
        return $this->numExecutedMigrations;
    }

    /**
     * @return int
     */
    public function numAvailableMigrations()
    {
        return $this->numAvailableMigrations;
    }

    /**
     * @return int
     */
    public function numNewMigrations()
    {
        return $this->numNewMigrations;
    }
}
