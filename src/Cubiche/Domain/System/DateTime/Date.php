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

/**
 * Date.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jérez <ivannis.suarez@gmail.com>
 */
class Date implements NativeValueObjectInterface
{
    /**
     * @var Year
     */
    protected $year;

    /**
     * @var Month
     */
    protected $month;

    /**
     * @var MonthDay
     */
    protected $day;

    /**
     * @param \DateTime $date
     *
     * @return Date
     */
    public static function fromNative($date)
    {
        $year = Year::fromNativeDateTime($date);
        $month = Month::fromNativeDateTime($date);
        $day = MonthDay::fromNativeDateTime($date);

        return new static($year, $month, $day);
    }

    /**
     * @return Date
     */
    public static function now()
    {
        return static::fromNative(new \DateTime('now'));
    }

    /**
     * @param Year     $year
     * @param Month    $month
     * @param MonthDay $day
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Year $year, Month $month, MonthDay $day)
    {
        $dateString = \sprintf('%d-%s-%d', $year->toNative(), $month->toNative(), $day->toNative());

        Assert::date($dateString, 'Y-F-j');

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @return Year
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * @return Month
     */
    public function month()
    {
        return $this->month;
    }

    /**
     * @return MonthDay
     */
    public function day()
    {
        return $this->day;
    }

    /**
     * @return \DateTime
     */
    public function toNative()
    {
        $year = $this->year()->toNative();
        $month = $this->month()->ordinalValue();
        $day = $this->day()->toNative();

        $date = new \DateTime();
        $date->setDate($year, $month, $day);
        $date->setTime(0, 0, 0);

        return $date;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($date)
    {
        return get_class($this) === get_class($date) &&
            $this->year()->equals($date->year()) &&
            $this->month()->equals($date->month()) &&
            $this->day()->equals($date->day())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->toNative()->format('Y-m-d');
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->__toString();
    }
}
