<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\Order;

use Cubiche\Domain\System\Enum;

/**
 * OrderState class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderState extends Enum
{
    const STATE_NEW = 'new';
    const STATE_BOOKED = 'booked';
    const STATE_REJECTED = 'rejected';
}
