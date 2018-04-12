<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\DateTime;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Domain\Model\NativeValueObjectInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * Timezone.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Timezone implements NativeValueObjectInterface
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @param \DateTimeZone $timezone
     *
     * @return Timezone
     */
    public static function fromNative($timezone)
    {
        return static::fromName($timezone->getName());
    }

    /**
     * @param string $timezone
     *
     * @return \Cubiche\Domain\System\DateTime\Timezone
     */
    public static function fromName($timezone)
    {
        return new static(new StringLiteral($timezone));
    }

    /**
     * @return Timezone
     */
    public static function fromDefault()
    {
        return new static(new StringLiteral(date_default_timezone_get()));
    }

    /**
     * @param StringLiteral $name
     *
     * @throws InvalidArgumentException
     */
    public function __construct(StringLiteral $name)
    {
        if (!in_array($name->toNative(), timezone_identifiers_list())) {
            $offsetName = self::offsetToName($name->toNative());
            if ($offsetName == null ||
                ($offsetName !== null && !in_array($offsetName->toNative(), timezone_identifiers_list()))
            ) {
                throw new InvalidArgumentException(
                    sprintf('The timezone "%s" is invalid.', $name->toNative()),
                    Assert::INVALID_CUSTOM_ASSERT,
                    'timezone',
                    $name->toNative()
                );
            }
        }

        $this->name = $name;
    }

    /**
     * @return \DateTimeZone
     */
    public function toNative()
    {
        $timezoneName = $this->name()->toNative();
        $offsetToName = self::offsetToName($this->name()->toNative());
        if ($offsetToName !== null) {
            $timezoneName = $offsetToName->toNative();
        }

        return new \DateTimeZone($timezoneName);
    }

    /**
     * @param string $offset
     *
     * @return StringLiteral
     */
    public static function offsetToName($offset)
    {
        if (strpos($offset, ':') === false) {
            return;
        }

        // Calculate seconds from offset
        list($hours, $minutes) = explode(':', $offset);
        $seconds = $hours * 60 * 60 + $minutes * 60;

        // Get timezone name from seconds
        $timezone = timezone_name_from_abbr('', $seconds, 1);
        if ($timezone === false) {
            $timezone = timezone_name_from_abbr('', $seconds, 0);

            if ($timezone === false) {
                $timezone = timezone_name_from_abbr('UTC');
            }
        }

        return StringLiteral::fromNative($timezone);
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Timezone $timezone
     *
     * @return bool
     */
    public function equals($timezone)
    {
        return get_class($this) === get_class($timezone) && $this->name()->equals($timezone->name());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->name()->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
