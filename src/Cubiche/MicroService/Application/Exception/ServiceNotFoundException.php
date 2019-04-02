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
 * ServiceNotFoundException class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ServiceNotFoundException extends Exception
{
    /**
     * @param string|null $message
     */
    public function __construct($message = null)
    {
        parent::__construct(405, $message);
    }
}
