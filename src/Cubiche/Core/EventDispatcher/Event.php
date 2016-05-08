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

/**
 * Event class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Event implements EventInterface
{
    /**
     * The event name.
     *
     * @var string
     */
    protected $name;

    /**
     * Has propagation stopped?
     *
     * @var bool
     */
    protected $propagationStopped = false;

    /**
     * Create a new event instance.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return $this->name ? $this->name : get_class($this);
    }

    /**
     * Create a new event instance.
     *
     * @param string $name
     *
     * @return static
     */
    public static function named($name)
    {
        return new static($name);
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
