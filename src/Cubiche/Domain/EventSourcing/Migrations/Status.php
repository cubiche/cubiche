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
    protected $currentMigration;

    /**
     * @var Version
     */
    protected $latestVersion;

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
     * @param Migration $currentMigration
     * @param Version   $latestVersion
     * @param int       $numExecutedMigrations
     * @param int       $numAvailableMigrations
     * @param int       $numNewMigrations
     */
    public function __construct(
        Migration $currentMigration = null,
        Version $latestVersion = null,
        $numExecutedMigrations = 0,
        $numAvailableMigrations = 0,
        $numNewMigrations = 0
    ) {
        $this->currentMigration = $currentMigration;
        $this->latestVersion = $latestVersion;
        $this->numExecutedMigrations = $numExecutedMigrations;
        $this->numAvailableMigrations = $numAvailableMigrations;
        $this->numNewMigrations = $numNewMigrations;
    }

    /**
     * @return Migration|null
     */
    public function currentMigration()
    {
        return $this->currentMigration;
    }

    /**
     * @return Version|null
     */
    public function latestVersion()
    {
        return $this->latestVersion;
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
