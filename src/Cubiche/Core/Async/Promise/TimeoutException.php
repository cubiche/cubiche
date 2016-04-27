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
 * Timeout Exception class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class TimeoutException extends CancellationException
{
    /**
     * @var int|float
     */
    protected $time;

    /**
     * @param int|float $time
     * @param string    $message
     */
    public function __construct($time, $message = null)
    {
        $this->time = $time;
        if ($message !== null) {
            $message = \sprintf('The promise has been cancelled, timeout expired after %f seconds', $this->time);
        }

        parent::__construct($message);
    }
}
