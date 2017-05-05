<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Projector;

use Cubiche\Domain\System\Enum;

/**
 * Action class.
 *
 * @method static Action NONE()
 * @method static Action CREATE()
 * @method static Action UPDATE()
 * @method static Action REMOVE()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
final class Action extends Enum
{
    const NONE = 'none';
    const CREATE = 'create';
    const UPDATE = 'update';
    const REMOVE = 'remove';
}
