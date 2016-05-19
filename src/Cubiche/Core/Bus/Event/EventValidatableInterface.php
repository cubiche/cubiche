<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Event;

use Cubiche\Core\Bus\MessageValidatableInterface;

/**
 * EventValidatable interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventValidatableInterface extends EventInterface, MessageValidatableInterface
{
}
