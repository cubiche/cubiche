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
 * Cancellation Exception class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CancellationException extends RejectionException
{
    /**
     * @param string $message
     */
    public function __construct($message = null)
    {
        if ($message !== null) {
            $message = 'The promise has been cancelled';
        }

        parent::__construct($this, $message);
    }
}
