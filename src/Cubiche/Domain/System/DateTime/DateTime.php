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
 * DateTime.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DateTime implements NativeValueObjectInterface
{
    /**
     * @var Date
     */
    protected $date;

    /**
     * @var Time
     */
    protected $time;

    /**
     * @var TimeZone
     */
    protected $timezone;

    /**
     * @param \DateTime $dateTime
     *
     * @return DateTime
     */
    public static function fromNative($dateTime)
    {
        $date = Date::fromNative($dateTime);
        $time = Time::fromNative($dateTime);
        $timezone = Timezone::fromNative($dateTime->getTimezone());

        return new static($date, $time, $timezone);
    }

    /**
     * @param int $timestamp
     *
     * @return DateTime
     */
    public static function fromTimestamp($timestamp)
    {
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);

        return static::fromNative($datetime);
    }

    /**
     * @return DateTime
     */
    public static function now()
    {
        $now = new \DateTime('now');
        $date = Date::fromNative($now);
        $time = Time::fromNative($now);
        $timezone = Timezone::fromDefault();

        return new static($date, $time, $timezone);
    }

    /**
     * @param Date     $date
     * @param Time     $time
     * @param Timezone $timezone
     */
    public function __construct(Date $date, Time $time = null, Timezone $timezone = null)
    {
        $this->date = $date;
        $this->time = $time === null ? Time::zero() : $time;
        $this->timezone = $timezone === null ? Timezone::fromDefault() : $timezone;
    }

    /**
     * @return Date
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return Year
     */
    public function year()
    {
        return $this->date()->year();
    }

    /**
     * @return Month
     */
    public function month()
    {
        return $this->date()->month();
    }

    /**
     * @return MonthDay
     */
    public function day()
    {
        return $this->date()->day();
    }

    /**
     * @return Time
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * @return Hour
     */
    public function hour()
    {
        return $this->time()->hour();
    }

    /**
     * @return Minute
     */
    public function minute()
    {
        return $this->time()->minute();
    }

    /**
     * @return Second
     */
    public function second()
    {
        return $this->time()->second();
    }

    /**
     * @return Timezone
     */
    public function timezone()
    {
        return $this->timezone;
    }

    /**
     * @return DateTime
     */
    public function midnight()
    {
        return self::fromNative($this->toNative()->modify('midnight'));
    }

    /**
     * @return int
     */
    public function timestamp()
    {
        return $this->toNative()->getTimestamp();
    }

    /**
     * @return \DateTime
     */
    public function toNative()
    {
        $year = $this->year()->toNative();
        $month = $this->month()->ordinalValue();
        $day = $this->day()->toNative();
        $hour = $this->hour()->toNative();
        $minute = $this->minute()->toNative();
        $second = $this->second()->toNative();
        $timezone = $this->timezone()->toNative();

        $dateTime = new \DateTime('now', $timezone);
        $dateTime->setDate($year, $month, $day);
        $dateTime->setTime($hour, $minute, $second);

        return $dateTime;
    }

    /**
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function equals($dateTime)
    {
        return get_class($this) === get_class($dateTime) &&
            $this->date()->equals($dateTime->date()) &&
            $this->time()->equals($dateTime->time()) &&
            $this->timezone()->equals($dateTime->timezone())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return \sprintf('%s %s %s', $this->date(), $this->time(), $this->timezone());
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
