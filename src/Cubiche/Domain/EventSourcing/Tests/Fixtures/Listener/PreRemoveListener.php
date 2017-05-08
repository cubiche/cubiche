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

use Cubiche\Domain\EventSourcing\Event\PreRemoveEvent;

/**
 * PreRemoveListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PreRemoveListener
{
    /**
     * @var int
     */
    protected $version;

    /**
     * PreRemoveListener constructor.
     *
     * @param int $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @param PreRemoveEvent $event
     */
    public function onPreRemove(PreRemoveEvent $event)
    {
        $event->aggregate()->setVersion($this->version);
    }
}
