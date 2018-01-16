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

use Cubiche\Core\Bus\MessageInterface;

/**
 * Event interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventInterface extends MessageInterface
{
    /**
     * Stop event propagation.
     *
     * @return $this
     */
    public function stopPropagation();

    /**
     * Check whether propagation was stopped.
     *
     * @return bool
     */
    public function isPropagationStopped();

    /**
     * Get the event name.
     *
     * @return string
     */
    public function eventName();
}
