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

use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * LocalizableValueInterface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface LocalizableValueInterface extends NativeValueObjectInterface
{
    const DEFAULT_LOCALE = LocaleCode::EN;
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
     * @return LocaleCode
     */
    public function locale();

    /**
     * @param LocaleCode $locale
     */
    public function setLocale(LocaleCode $locale);

    /**
     * @param mixed      $value
     * @param LocaleCode $locale
     */
    public function addNative($value, LocaleCode $locale);

    /**
     * @param LocaleCode $locale
     */
    public function remove(LocaleCode $locale);

    /**
     * @param LocaleCode $locale
     *
     * @return bool
     */
    public function has(LocaleCode $locale);

    /**
     * @param LocaleCode $locale
     *
     * @return mixed
     */
    public function translate(LocaleCode $locale);

    /**
     * @param LocaleCode $locale
     *
     * @return NativeValueObjectInterface
     */
    public function value(LocaleCode $locale);
}
