<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\CommandBus\Middlewares\Locking;

use Cubiche\Core\Enum\Enum;

/**
 * CommandState class.
 *
 * @method CommandState RECEIVED()
 * @method CommandState HANDLED()
 * @method CommandState FAILED()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandState extends Enum
{
    const RECEIVED = 'received';
    const HANDLED = 'handled';
    const FAILED = 'failed';
}
