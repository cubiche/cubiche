<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Locale\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * LanguageCodeException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageCodeException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid language code',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be a valid language code',
        ],
    ];
}
