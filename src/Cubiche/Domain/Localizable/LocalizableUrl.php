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

use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Web\Url;

/**
 * LocalizableUrl.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LocalizableUrl extends Url implements LocalizableValueInterface
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
     * @return LocalizableUrl
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
        $this->add(new Url($value), $locale);
    }

    /**
     * @param Url        $url
     * @param LocaleCode $locale
     */
    public function add(Url $url, LocaleCode $locale)
    {
        $this->addTranslation($url, $locale);
    }

    /**
     * @return string
     */
    public function host()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->host();
    }

    /**
     * @return string
     */
    public function fragmentId()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->fragmentId();
    }

    /**
     * @return string
     */
    public function password()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->password();
    }

    /**
     * @return string
     */
    public function path()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->path();
    }

    /**
     * @return int
     */
    public function port()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->port();
    }

    /**
     * @return string
     */
    public function queryString()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->queryString();
    }

    /**
     * @return string
     */
    public function scheme()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->scheme();
    }

    /**
     * @return string
     */
    public function user()
    {
        if (!$this->has($this->locale())) {
            return;
        }

        return $this->value($this->locale())->user();
    }
}
