<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Exception;

/**
 * InvalidTokenException class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidTokenException extends Exception
{
    /**
     * InvalidTokenException constructor.
     *
     * @param string|null $message
     */
    public function __construct($message = 'Invalid JWT Token')
    {
        parent::__construct(402, $message);
    }
}
