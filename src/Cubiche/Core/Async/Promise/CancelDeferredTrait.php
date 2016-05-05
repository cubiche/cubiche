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

/**
 * Cancel Deferred trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait CancelDeferredTrait
{
    /**
     * @return bool
     */
    public function cancel()
    {
        if ($this->promise()->state()->equals(State::PENDING())) {
            $this->reject(new CancellationException());

            return true;
        }

        return false;
    }
}
