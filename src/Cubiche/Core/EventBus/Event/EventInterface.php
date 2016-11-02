<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventBus\Event;

use Cubiche\Core\EventDispatcher\EventInterface as DispatcherEventInterface;
use Cubiche\Core\Bus\MessageInterface;

/**
 * Event interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventInterface extends MessageInterface, DispatcherEventInterface
{
}
