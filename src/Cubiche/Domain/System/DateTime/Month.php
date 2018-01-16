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

use Cubiche\Domain\System\Enum;

/**
 * Month.
 *
 * @method Month JANUARY()
 * @method Month FEBRUARY()
 * @method Month MARCH()
 * @method Month APRIL()
 * @method Month MAY()
 * @method Month JUNE()
 * @method Month JULY()
 * @method Month AUGUST()
 * @method Month SEPTEMBER()
 * @method Month OCTOBER()
 * @method Month NOVEMBER()
 * @method Month DECEMBER()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class Month extends Enum
{
    const JANUARY = 'January';
    const FEBRUARY = 'February';
    const MARCH = 'March';
    const APRIL = 'April';
    const MAY = 'May';
    const JUNE = 'June';
    const JULY = 'July';
    const AUGUST = 'August';
    const SEPTEMBER = 'September';
    const OCTOBER = 'October';
    const NOVEMBER = 'November';
    const DECEMBER = 'December';

    /**
     * @param \DateTime $date
     *
     * @return Month
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $month = \ucfirst($date->format('F'));

        return static::fromNative($month);
    }

    /**
     * Returns a numeric representation of the Month.
     * 1 for January to 12 for December.
     *
     * @return number
     */
    public function ordinalValue()
    {
        return intval(array_search($this->value(), array_values(static::toArray()), true)) + 1;
    }
}
