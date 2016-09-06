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
     * @var bool
     */
    protected $major;

    /**
     * MigrationsMigrateCommand constructor.
     *
     * @param bool $major
     */
    public function __construct($major = false)
    {
        $this->major = $major;
    }

    /**
     * @return bool
     */
    public function isMajor()
    {
        return $this->major;
    }

    /**
     * @param bool $major
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }
}
