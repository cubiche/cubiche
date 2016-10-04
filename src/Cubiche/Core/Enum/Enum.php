<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Enum;

use Cubiche\Core\Equatable\EquatableInterface;
use Cubiche\Core\Hashable\HashCoder;
use MyCLabs\Enum\Enum as BaseEnum;

/**
 * Enum class.
 *
 * @method static static __DEFAULT()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Enum extends BaseEnum implements EquatableInterface
{
    /**
     * @var array
     */
    protected static $defaultCache = array();

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function is($value)
    {
        return $this->value === $value;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return \get_class($this) === \get_class($other) && $this->getValue() === $other->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return HashCoder::defaultHashCoder()->hashCode($this->getValue());
    }

    /**
     * @param Enum $enum
     * @param Enum $default
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public static function ensure(Enum $enum = null, Enum $default = null)
    {
        if ($enum instanceof static) {
            return $enum;
        }
        if ($enum === null) {
            return $default === null ? static::__DEFAULT() : static::ensure($default);
        }

        throw new \InvalidArgumentException(\sprintf('The enum parameter must be a %s instance', static::class));
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (\strtoupper($name) === '__DEFAULT' && isset(static::$defaultCache[static::class])) {
            return new static(static::$defaultCache[static::class]);
        }

        if (isset($array[$name])) {
            return new static($array[$name]);
        }

        throw new \BadMethodCallException("No static method or enum constant '$name' in class ".static::class);
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        if (!isset(static::$cache[static::class])) {
            static::$cache[static::class] = self::constants(static::class);
            if (!isset(static::$defaultCache[static::class]) && !empty(static::$cache[static::class])) {
                static::$defaultCache[static::class] = \array_values(static::$cache[static::class])[0];
            }
        }

        return static::$cache[static::class];
    }

    /**
     * @param string $class
     *
     * @return array
     */
    private static function constants($class)
    {
        $reflection = new \ReflectionClass($class);
        $constants = $reflection->getConstants();
        foreach ($constants as $name => $value) {
            if (\strtoupper($name) === '__DEFAULT') {
                static::$defaultCache[$class] = $value;
                unset($constants[$name]);
            }
        }

        return $constants;
    }
}
