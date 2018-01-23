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

use Cubiche\Domain\Locale\LocaleCode as LocaleCodeEnum;
use Respect\Validation\Rules\AbstractRule;

/**
 * LocaleCode class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LocaleCode extends AbstractRule
{
    /**
     * @param $input
     *
     * @return bool
     */
    public function validate($input)
    {
        return LocaleCodeEnum::isValid($input);
    }
}
