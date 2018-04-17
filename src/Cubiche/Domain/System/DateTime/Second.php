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
 * Second.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Second extends Integer
{
    const MIN_SECOND = 0;
    const MAX_SECOND = 59;

    /**
     * @param int $second
     *
     * @return Second
     */
    public static function fromNative($second)
    {
        return new static($second);
    }

    /**
     * @param \DateTime $date
     *
     * @return Second
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $second = \intval($date->format('s'));

        return new static($second);
    }

    /**
     * Second constructor.
     *
     * @param int $second
     *
     * @throws InvalidArgumentException
     */
    public function __construct($second)
    {
        Assert::between(
            $second,
            self::MIN_SECOND,
            self::MAX_SECOND,
            sprintf(
                'Provided second "%s" must be in range %d - %d',
                $second,
                self::MIN_SECOND,
                self::MAX_SECOND
            )
        );

        parent::__construct($second);
    }
}
