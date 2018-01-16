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

use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * Time.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Time implements NativeValueObjectInterface
{
    /**
     * @var Hour
     */
    protected $hour;

    /**
     * @var Minute
     */
    protected $minute;

    /**
     * @var Second
     */
    protected $second;

    /**
     * @param \DateTime $date
     *
     * @return Time
     */
    public static function fromNative($date)
    {
        $hour = Hour::fromNativeDateTime($date);
        $minute = Minute::fromNativeDateTime($date);
        $second = Second::fromNativeDateTime($date);

        return new static($hour, $minute, $second);
    }

    /**
     * @return Time
     */
    public static function now()
    {
        return static::fromNative(new \DateTime('now'));
    }

    /**
     * @return Time
     */
    public static function zero()
    {
        return new static(new Hour(0), new Minute(0), new Second(0));
    }

    /**
     * Time constructor.
     *
     * @param Hour   $hour
     * @param Minute $minute
     * @param Second $second
     */
    public function __construct(Hour $hour, Minute $minute, Second $second)
    {
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * @return Hour
     */
    public function hour()
    {
        return $this->hour;
    }

    /**
     * @return Minute
     */
    public function minute()
    {
        return $this->minute;
    }

    /**
     * @return Second
     */
    public function second()
    {
        return $this->second;
    }

    /**
     * @return \DateTime
     */
    public function toNative()
    {
        $hour = $this->hour()->toNative();
        $minute = $this->minute()->toNative();
        $second = $this->second()->toNative();

        $time = new \DateTime('now');
        $time->setTime($hour, $minute, $second);

        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($time)
    {
        return get_class($this) === get_class($time) &&
            $this->hour()->equals($time->hour()) &&
            $this->minute()->equals($time->minute()) &&
            $this->second()->equals($time->second())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->toNative()->format('G:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
