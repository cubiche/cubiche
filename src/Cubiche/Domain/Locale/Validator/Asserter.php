<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Locale\Validator;

use Cubiche\Core\Validator\Assert;
use Cubiche\Domain\Locale\CountryCode;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Locale\LocaleCode;

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
    public static function countryCode($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            Assert::generateMessage($message ?: 'Value "%s" expected to be a valid country code.'),
            Assert::stringify($value)
        );

        return Assert::inArray($value, CountryCode::toArray(), $message, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function languageCode($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            Assert::generateMessage($message ?: 'Value "%s" expected to be a valid language code.'),
            Assert::stringify($value)
        );

        return Assert::inArray($value, LanguageCode::toArray(), $message, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function localeCode($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            Assert::generateMessage($message ?: 'Value "%s" expected to be a valid locale code.'),
            Assert::stringify($value)
        );

        return Assert::inArray($value, LocaleCode::toArray(), $message, $propertyPath);
    }
}
