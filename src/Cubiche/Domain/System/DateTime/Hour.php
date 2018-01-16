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
use Cubiche\Domain\System\DateTime\Exception\InvalidArgumentException;
use Cubiche\Domain\System\Integer;

/**
 * Hour.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Hour extends Integer
{
    const MIN_HOUR = 0;
    const MAX_HOUR = 23;

    /**
     * @param int $hour
     *
     * @return Hour
     */
    public static function fromNative($hour)
    {
        return new static($hour);
    }

    /**
     * @param \DateTime $date
     *
     * @return Hour
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $hour = \intval($date->format('G'));

        return new static($hour);
    }

    /**
     * Hour constructor.
     *
     * @param int $hour
     *
     * @throws InvalidArgumentException
     */
    public function __construct($hour)
    {
        if (!Assert::between(self::MIN_HOUR, self::MAX_HOUR)->validate($hour)) {
            throw InvalidArgumentException::invalidHour($hour, self::MIN_HOUR, self::MAX_HOUR);
        }

        parent::__construct($hour);
    }
}
