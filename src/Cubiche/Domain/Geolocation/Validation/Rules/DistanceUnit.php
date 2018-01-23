<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Geolocation\Validation\Rules;

use Cubiche\Domain\Geolocation\DistanceUnit as DistanceUnitEnum;
use Respect\Validation\Rules\AbstractRule;

/**
 * DistanceUnit class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DistanceUnit extends AbstractRule
{
    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        return DistanceUnitEnum::isValid($input);
    }
}
