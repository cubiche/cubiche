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
use Cubiche\Domain\Model\ValueObjectInterface;

/**
 * LocalizableValue.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait LocalizableValue
{
    /**
     * @var Locale
     */
    protected $locale;

    /**
     * @var NativeValueObjectInterface[]
     */
    protected $translations;

    /**
     * @var LocalizableValueMode
     */
    protected $mode;

    /**
     * @return LocalizableValueMode
     */
    public function mode()
    {
        return $this->mode;
    }

    /**
     * @param LocalizableValueMode $mode
     */
    public function setMode(LocalizableValueMode $mode)
    {
        $this->mode = $mode;
    }

    /**
     * @param Locale               $locale
     * @param LocalizableValueMode $mode
     *
     * @return Locale
     */
    private function resolveLocale(Locale $locale, LocalizableValueMode $mode)
    {
        if ($mode == LocalizableValueMode::STRICT()) {
            return $locale;
        }

        if ($mode == LocalizableValueMode::ANY()) {
            if ($this->has($locale)) {
                return $locale;
            }

            if ($this->has(Locale::fromNative(LocalizableValueInterface::DEFAULT_LOCALE))) {
                return Locale::fromNative(LocalizableValueInterface::DEFAULT_LOCALE);
            }

            if (count($this->translations) > 0) {
                return Locale::fromNative(array_keys($this->translations)[0]);
            }
        }

        return $locale;
    }

    /**
     * @return Locale
     */
    public function locale()
    {
        return $this->resolveLocale($this->locale, $this->mode);
    }

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param NativeValueObjectInterface $value
     * @param Locale                     $locale
     */
    private function addTranslation(NativeValueObjectInterface $value, Locale $locale)
    {
        $this->translations[$locale->toNative()] = $value;
    }

    /**
     * @param Locale $locale
     */
    public function remove(Locale $locale)
    {
        unset($this->translations[$locale->toNative()]);
    }

    /**
     * @param Locale $locale
     *
     * @return bool
     */
    public function has(Locale $locale)
    {
        return isset($this->translations[$locale->toNative()]);
    }

    /**
     * @return mixed
     */
    public function toNative()
    {
        return $this->translate($this->locale());
    }

    /**
     * @param Locale                    $locale
     * @param LocalizableValueMode|null $mode
     *
     * @return mixed|null
     */
    public function translate(Locale $locale, LocalizableValueMode $mode = null)
    {
        $locale = $this->resolveLocale($locale, $mode === null ? LocalizableValueMode::STRICT() : $mode);
        if ($this->has($locale)) {
            return $this->translations[$locale->toNative()]->toNative();
        }

        return;
    }

    /**
     * @return NativeValueObjectInterface[]
     */
    public function translations()
    {
        return $this->translations;
    }

    /**
     * @param Locale $locale
     *
     * @return NativeValueObjectInterface
     */
    public function value(Locale $locale)
    {
        if ($this->has($locale)) {
            return $this->translations[$locale->toNative()];
        }

        return;
    }

    /**
     * @param array  $translations
     * @param string $locale
     *
     * @return LocalizableString
     */
    public static function fromArray(array $translations, $locale = LocalizableValueInterface::DEFAULT_LOCALE)
    {
        $localizableString = new static(Locale::fromNative($locale));
        foreach ($translations as $localeCode => $translation) {
            $localizableString->addNative($translation, Locale::fromNative($localeCode));
        }

        return $localizableString;
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        $translations = array();
        /** @var NativeValueObjectInterface $translation */
        foreach ($this->translations as $locale => $translation) {
            $translations[$locale] = $translation->toNative();
        }

        return $translations;
    }

    /**
     * @param ValueObjectInterface $localizableValue
     *
     * @return bool
     */
    public function equals($localizableValue)
    {
        return get_class($this) === get_class($localizableValue) &&
             $this->locale()->equals($localizableValue->locale()) &&
             ((!$this->has($this->locale()) && !$localizableValue->has($localizableValue->locale())) ||
             ($this->has($this->locale()) && $localizableValue->has($localizableValue->locale()) &&
             $this->value($this->locale())->equals($localizableValue->value($localizableValue->locale()))))
        ;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $value = $this->value($this->locale());

        return $value === null ? '' : $value->__toString();
    }
}
