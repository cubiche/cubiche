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

/**
 * Latitude class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Latitude extends AbstractRule
{
    const REGEX = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        return preg_match(self::REGEX, $input, $matches) === 1;
    }
}
