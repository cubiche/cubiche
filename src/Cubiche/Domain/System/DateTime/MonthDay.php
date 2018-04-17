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
use Cubiche\Domain\System\Integer;

/**
 * MonthDay.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class MonthDay extends Integer
{
    const MIN_MONTH_DAY = 1;
    const MAX_MONTH_DAY = 31;

    /**
     * @param int $day
     *
     * @return MonthDay
     */
    public static function fromNative($day)
    {
        return new static($day);
    }

    /**
     * @param \DateTime $date
     *
     * @return MonthDay
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $monthDay = \intval($date->format('j'));

        return new static($monthDay);
    }

    /**
     * MonthDay constructor.
     *
     * @param int $day
     *
     * @throws InvalidArgumentException
     */
    public function __construct($day)
    {
        Assert::between(
            $day,
            self::MIN_MONTH_DAY,
            self::MAX_MONTH_DAY,
            sprintf(
                'Provided day "%s" must be in range %d - %d',
                $day,
                self::MIN_MONTH_DAY,
                self::MAX_MONTH_DAY
            )
        );

        parent::__construct($day);
    }
}
