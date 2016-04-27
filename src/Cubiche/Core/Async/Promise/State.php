<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Promise;

use Cubiche\Core\Enum\Enum;

/**
 * State enum.
 *
 * @method State PENDING()
 * @method State FULFILLED()
 * @method State REJECTED()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class State extends Enum
{
    const PENDING = 'pending';
    const FULFILLED = 'fulfilled';
    const REJECTED = 'rejected';
}
