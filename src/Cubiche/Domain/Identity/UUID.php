<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity;

use Ramsey\Uuid\Uuid as BaseUuid;

/**
 * Universally Unique Id Class (UUID).
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class UUID extends StringId
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValidUUID($value)
    {
        $pattern = '/'.BaseUuid::VALID_PATTERN.'/';

        return \preg_match($pattern, $value) === 1;
    }

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value = null)
    {
        if ($value !== null && !self::isValidUUID($value)) {
            throw new \InvalidArgumentException(sprintf('Argument "%s" is an invalid UUID.', $value));
        }

        $this->value = $value === null ? self::nextUUIDValue() :  $value;
    }

    /**
     * @return static
     */
    public static function next()
    {
        return new static();
    }

    /**
     * @return string
     */
    public static function nextUUIDValue()
    {
        return BaseUuid::uuid4()->toString();
    }
}
