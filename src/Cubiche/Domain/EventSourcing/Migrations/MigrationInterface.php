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
     * @return string
     */
    public function aggregateClassName();
}
