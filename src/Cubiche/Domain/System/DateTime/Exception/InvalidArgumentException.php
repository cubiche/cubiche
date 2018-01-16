<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\DateTime\Exception;

/**
 * InvalidArgumentException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidArgumentException extends \Exception
{
    /**
     * @param string $year
     * @param string $month
     * @param string $day
     *
     * @return static
     */
    public static function invalidDate($year, $month, $day)
    {
        return new static(sprintf('The date "%d-%d-%d" is invalid.', $year, $month, $day));
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return static
     */
    public static function invalidDateRange($from, $to)
    {
        return new static(sprintf('From date %s must be less than or equals to %s.', $from, $to));
    }

    /**
     * @param int $hour
     * @param int $min
     * @param int $max
     *
     * @return static
     */
    public static function invalidHour($hour, $min, $max)
    {
        return new static(
            sprintf('Argument "%s" is invalid. The hour argument must be in range %d - %d', $hour, $min, $max)
        );
    }

    /**
     * @param int $minute
     * @param int $min
     * @param int $max
     *
     * @return static
     */
    public static function invalidMinute($minute, $min, $max)
    {
        return new static(
            sprintf('Argument "%s" is invalid. The minute argument must be in range %d - %d', $minute, $min, $max)
        );
    }

    /**
     * @param int $day
     * @param int $min
     * @param int $max
     *
     * @return static
     */
    public static function invalidMonthDay($day, $min, $max)
    {
        return new static(
            sprintf('Argument "%s" is invalid. The day argument must be in range %d - %d', $day, $min, $max)
        );
    }

    /**
     * @param int $second
     * @param int $min
     * @param int $max
     *
     * @return static
     */
    public static function invalidSecond($second, $min, $max)
    {
        return new static(
            sprintf('Argument "%s" is invalid. The "second" argument must be in range %d - %d', $second, $min, $max)
        );
    }

    /**
     * @param int $year
     *
     * @return static
     */
    public static function invalidYear($year)
    {
        return new static(
            sprintf('Argument "%s" is invalid. The year argument must be positive', $year)
        );
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public static function invalidTimezone($name)
    {
        return new static(sprintf('The timezone "%s" is invalid.', $name));
    }
}
