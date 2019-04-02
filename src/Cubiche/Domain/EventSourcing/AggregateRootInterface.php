<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing;

use Cubiche\Core\Bus\Recorder\MessageRecorderInterface;
use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\Model\EntityInterface;

/**
 * Aggregate Root Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface AggregateRootInterface extends EntityInterface, MessageRecorderInterface
{
    /**
     * @param EventStream $history
     *
     * @return AggregateRootInterface
     */
    public static function loadFromHistory(EventStream $history);

    /**
     * @param EventStream $history
     */
    public function replay(EventStream $history);

    /**
     * @return int
     */
    public function version();

    /**
     * @param int $version
     */
    public function setVersion($version);
}
