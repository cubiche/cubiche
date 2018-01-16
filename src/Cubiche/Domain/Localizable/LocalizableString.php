<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Localizable;

use Cubiche\Domain\System\StringLiteral;

/**
 * LocalizableString.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LocalizableString extends StringLiteral implements LocalizableValueInterface
{
    use LocalizableValue;

    /**
     * @param LocaleCode           $locale
     * @param LocalizableValueMode $mode
     */
    public function __construct(LocaleCode $locale, LocalizableValueMode $mode = null)
    {
        $this->locale = $locale;
        $this->translations = [];
        if ($mode === null) {
            $mode = LocalizableValueMode::fromNative(LocalizableValueInterface::DEFAULT_MODE);
        }

        $this->mode = $mode;
    }

    /**
     * @param mixed|string $value
     *
     * @return LocalizableString
     */
    public static function fromNative($value)
    {
        $locale = LocaleCode::fromNative(LocalizableValueInterface::DEFAULT_LOCALE);
        $localizableValue = new static($locale);
        $localizableValue->addNative($value, $locale);

        return $localizableValue;
    }

    /**
     * @param string     $value
     * @param LocaleCode $locale
     */
    public function addNative($value, LocaleCode $locale)
    {
        $this->add(new StringLiteral($value), $locale);
    }

    /**
     * @param StringLiteral $value
     * @param LocaleCode    $locale
     */
    public function add(StringLiteral $value, LocaleCode $locale)
    {
        $this->addTranslation($value, $locale);
    }
}
