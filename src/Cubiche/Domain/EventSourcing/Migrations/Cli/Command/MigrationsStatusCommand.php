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
 * MigrationsStatusCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MigrationsStatusCommand extends ConsoleCommand
{
    /**
     * @var string
     */
    protected $aggregate;

    /**
     * MigrationsStatusCommand constructor.
     *
     * @param string $aggregate
     */
    public function __construct($aggregate = null)
    {
        $this->aggregate = $aggregate;
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
