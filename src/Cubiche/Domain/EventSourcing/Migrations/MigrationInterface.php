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

use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Versioning\VersionIncrementType;

/**
 * Migration interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MigrationInterface
{
    /**
     * @param EventStream $eventStream
     *
     * @return EventStream
     */
    public function migrate(EventStream $eventStream);

    /**
     * @return VersionIncrementType
     */
    public function migrationType();

    /**
     * @return string
     */
    public function aggregateClassName();
}
