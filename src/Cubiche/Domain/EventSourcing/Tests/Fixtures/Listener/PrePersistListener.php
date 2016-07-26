<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\Listener;

use Cubiche\Domain\EventSourcing\Event\PrePersistEvent;

/**
 * PrePersistListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PrePersistListener
{
    /**
     * @var int
     */
    protected $version;

    /**
     * PrePersistListener constructor.
     *
     * @param int $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @param PrePersistEvent $event
     */
    public function onPrePersist(PrePersistEvent $event)
    {
        $event->aggregate()->version()->setAggregateVersion($this->version);
    }
}
