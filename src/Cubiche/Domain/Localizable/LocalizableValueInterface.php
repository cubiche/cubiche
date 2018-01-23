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
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * LocalizableValueInterface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface LocalizableValueInterface extends NativeValueObjectInterface
{
    const DEFAULT_LOCALE = 'en_US';
    const DEFAULT_MODE = LocalizableValueMode::ANY;

    /**
     * @return LocalizableValueMode
     */
    public function mode();

    /**
     * @param LocalizableValueMode $mode
     */
    public function setMode(LocalizableValueMode $mode);

    /**
     * @return Locale
     */
    public function locale();

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale);

    /**
     * @param mixed  $value
     * @param Locale $locale
     */
    public function addNative($value, Locale $locale);

    /**
     * @param Locale $locale
     */
    public function remove(Locale $locale);

    /**
     * @param Locale $locale
     *
     * @return bool
     */
    public function has(Locale $locale);

    /**
     * @param Locale $locale
     *
     * @return mixed
     */
    public function translate(Locale $locale);

    /**
     * @param Locale $locale
     *
     * @return NativeValueObjectInterface
     */
    public function value(Locale $locale);

    /**
     * @param array  $translations
     * @param string $locale
     *
     * @return LocalizableString
     */
    public static function fromArray(array $translations, $locale = self::DEFAULT_LOCALE);

    /**
     * @return mixed
     */
    public function toArray();
}
