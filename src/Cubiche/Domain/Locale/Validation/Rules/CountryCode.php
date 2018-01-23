<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Locale\Validation\Rules;

use Cubiche\Domain\Locale\CountryCode as CountryCodeEnum;

/**
 * CountryCode class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CountryCode extends AbstractRule
{
    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        return CountryCodeEnum::isValid($input);
    }
}
