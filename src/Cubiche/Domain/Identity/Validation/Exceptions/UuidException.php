<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * UuidException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class UuidException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The value {{input}} must be a valid UUID.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'The value {{input}} must not be a valid UUID.',
        ],
    ];
}
