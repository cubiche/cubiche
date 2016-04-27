<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Exception;

use Exception;
use RuntimeException;

/**
 * NotFoundException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NotFoundException extends RuntimeException
{
    /**
     * Creates an exception for a not found middleware.
     *
     * @param string         $type
     * @param Exception|null $cause
     *
     * @return NotFoundException
     */
    public static function middlewareOfType($type, Exception $cause = null)
    {
        return new static(sprintf(
            'Not found a middleware of type %s',
            $type
        ), 0, $cause);
    }
}
