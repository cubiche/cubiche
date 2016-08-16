<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Cli\Command;

use Cubiche\Core\Console\Command\ConsoleCommand;

/**
 * MigrationsGenerateCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationsGenerateCommand extends ConsoleCommand
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $aggregate;

    /**
     * MigrationsMigrateCommand constructor.
     *
     * @param string $version
     * @param string $aggregate
     */
    public function __construct($version = null, $aggregate = null)
    {
        $this->version = $version;
        $this->aggregate = $aggregate;
    }

    /**
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function aggregate()
    {
        return $this->aggregate;
    }

    /**
     * @param string $aggregate
     */
    public function setAggregate($aggregate)
    {
        $this->aggregate = $aggregate;
    }
}
