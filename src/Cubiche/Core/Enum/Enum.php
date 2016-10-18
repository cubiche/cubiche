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

/**
 * Enum class based on http://github.com/myclabs/php-enum.
 *
 * @method static static __DEFAULT()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Enum implements EquatableInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected static $cache = array();

    /**
     * @var array
     */
    protected static $defaultCache = array();

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

        throw new \BadMethodCallException(
            \sprintf('No static method or enum constant %S in class %s', $name, static::class)
        );
    }

    /**
     * Creates a new value of some type.
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException(
                \sprintf('Value %s is not part of the enum %s.', $value, static::class)
            );
        }

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function name()
    {
        return static::search($this->value);
    }

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
        return $other instanceof static && $this->is($other->value());
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return HashCoder::defaultHashCoder()->hashCode($this->value());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public static function isValidName($name)
    {
        $array = static::toArray();

        return isset($array[$name]);
    }

    /**
     * @return array
     */
    public static function names()
    {
        return \array_keys(static::toArray());
    }

    /**
     * @return static[]
     */
    public static function values()
    {
        $values = array();
        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }

        return $values;
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
     * @param $value
     *
     * @return mixed
     */
    protected static function search($value)
    {
        return \array_search($value, static::toArray(), true);
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
