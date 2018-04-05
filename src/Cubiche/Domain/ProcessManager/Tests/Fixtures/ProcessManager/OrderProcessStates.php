<?php

/**
 * This file is part of the Cubiche/ProcessManager component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager\Tests\Fixtures\ProcessManager;

use Cubiche\Domain\System\Enum;

/**
 * OrderProcessStates class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class OrderProcessStates extends Enum
{
    const AWAITING_RESERVATION_CONFIRMATION = 'awaiting_reservation_confirmation';
    const AWAITING_PAYMENT = 'awaiting_payment';
    const REJECTED = 'rejected';
    const COMPLETED = 'completed';
    const EXPIRED = 'expired';
}
