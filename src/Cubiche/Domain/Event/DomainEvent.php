<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Event;

use Cubiche\Core\EventBus\Event;
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
     */
    public function __construct()
    {
        $this->occurredOn = new DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
