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
 * Rejection Exception class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class RejectionException extends \RuntimeException
{
    /**
     * @var mixed
     */
    protected $reason;

    /**
     * @param mixed  $reason
     * @param string $message
     */
    public function __construct($reason, $message = null)
    {
        $this->reason = $reason;
        if ($message !== null) {
            $message = 'The promise has been rejected';
            if (\is_string($reason) || (\is_object($reason) && \method_exists($reason, '__toString'))) {
                $message .= ' with reason: '.$this->reason;
            } else {
                $message .= ' with unknown reason: '.\is_object($reason) ? \get_class($reason) : \gettype($reason);
            }
        }

        parent::__construct($message);
    }

    /**
     * @return mixed
     */
    public function reason()
    {
        return $this->reason;
    }
}
