<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventPublisher;

use Cubiche\Core\EventBus\Event\Event;
use DateTime;

/**
 * DomainEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DomainEvent extends Event implements DomainEventInterface
{
    /**
     * @var DateTime
     */
    protected $occurredOn;

    /**
     * DomainEvent constructor.
     *
     * @param string|null $eventName
     */
    public function __construct($eventName = null)
    {
        parent::__construct($eventName);

        $this->occurredOn = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
