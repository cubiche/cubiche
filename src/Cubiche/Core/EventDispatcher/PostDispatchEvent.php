<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventDispatcher;

use Cubiche\Core\Bus\NamedMessageInterface;

/**
 * PostDispatchEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostDispatchEvent extends Event implements NamedMessageInterface
{
    const eventName = 'event_dispatcher.post_dispatch';

    /**
     * @var EventInterface
     */
    protected $event;

    /**
     * PreDispatchEvent constructor.
     *
     * @param EventInterface $event
     */
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @return EventInterface
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function messageName(): string
    {
        return self::eventName;
    }
}
