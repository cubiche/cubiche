<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Geolocation\Validator;

use Cubiche\Core\Validator\Assert;
use Cubiche\Domain\Geolocation\DistanceUnit;

/**
 * Asserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Asserter
{
    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function distanceUnit($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            Assert::generateMessage($message ?: 'Value "%s" expected to be a valid distance unit.'),
            Assert::stringify($value)
        );

        return Assert::inArray($value, DistanceUnit::toArray(), $message, $propertyPath);
    }
}
