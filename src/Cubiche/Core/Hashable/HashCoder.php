<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Hashable;

/**
 * Default Hash Coder class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class HashCoder implements HashCoderInterface
{
    /**
     * @var HashCoderInterface
     */
    private static $default = null;

    /**
     * @return \Cubiche\Core\Hashable\HashCoderInterface
     */
    public static function defaultHashCoder()
    {
        if (self::$default === null) {
            self::$default = new self();
        }

        return self::$default;
    }

    /**
     * @param HashCoderInterface $hashCoder
     * @param HashCoderInterface $default
     *
     * @return \Cubiche\Core\Hashable\HashCoderInterface
     */
    public static function ensure(HashCoderInterface $hashCoder = null, HashCoderInterface $default = null)
    {
        return $hashCoder !== null || $default !== null ?
            ($hashCoder !== null ? $hashCoder : $default) : self::defaultHashCoder();
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public static function defaultHashCode($value)
    {
        if (\is_object($value)) {
            return \spl_object_hash($value);
        } elseif (\is_scalar($value) || \is_resource($value)) {
            return (string) $value;
        } elseif (\is_array($value)) {
            return \md5(\serialize($value));
        }

        return '0';
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode($value)
    {
        if ($value instanceof HashableInterface) {
            return $value->hashCode();
        }

        return self::defaultHashCode($value);
    }
}
