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

use Cubiche\Domain\Locale\Locale;
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
     * @param Locale               $locale
     * @param LocalizableValueMode $mode
     */
    public function __construct(Locale $locale, LocalizableValueMode $mode = null)
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
        $locale = Locale::fromNative(LocalizableValueInterface::DEFAULT_LOCALE);
        $localizableValue = new static($locale);
        $localizableValue->addNative($value, $locale);

        return $localizableValue;
    }

    /**
     * @param string $value
     * @param Locale $locale
     */
    public function addNative($value, Locale $locale)
    {
        $this->add(new StringLiteral($value), $locale);
    }

    /**
     * @param StringLiteral $value
     * @param Locale        $locale
     */
    public function add(StringLiteral $value, Locale $locale)
    {
        $this->addTranslation($value, $locale);
    }
}
