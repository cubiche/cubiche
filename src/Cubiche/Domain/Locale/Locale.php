<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Locale;

use Cubiche\Domain\Model\NativeValueObject;

/**
 * Locale.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Locale extends NativeValueObject
{
    /**
     * @var LanguageCode
     */
    protected $languageCode;

    /**
     * @var CountryCode
     */
    protected $countryCode;

    /**
     * @param LanguageCode $languageCode
     * @param CountryCode  $countryCode
     */
    public function __construct(LanguageCode $languageCode, CountryCode $countryCode)
    {
        $this->languageCode = $languageCode;
        $this->countryCode = $countryCode;
    }

    /**
     * @return LanguageCode
     */
    public function languageCode()
    {
        return $this->languageCode;
    }

    /**
     * @return CountryCode
     */
    public function countryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function fromNative($value)
    {
        $pieces = explode('_', $value);

        return new static(
            LanguageCode::fromNative($pieces[0]),
            CountryCode::fromNative($pieces[1])
        );
    }

    /**
     * @return mixed
     */
    public function toNative()
    {
        return sprintf(
            '%s_%s',
            $this->languageCode->toNative(),
            $this->countryCode->toNative()
        );
    }
}
