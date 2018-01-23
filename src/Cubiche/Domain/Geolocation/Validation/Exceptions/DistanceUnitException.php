<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Geolocation\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * DistanceUnitException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DistanceUnitException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'The {{name}} {{input}} must be a valid distance unit.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'The {{name}} {{input}} must not be a valid distance unit.',
        ],
    ];
}
