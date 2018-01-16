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
 * Year.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Year extends Integer
{
    /**
     * @param int $date
     *
     * @return Year
     */
    public static function fromNative($year)
    {
        return new static($year);
    }

    /**
     * @param \DateTime $date
     *
     * @return \Cubiche\Domain\System\DateTime\Year
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $year = \intval($date->format('Y'));

        return new static($year);
    }

    /**
     * Year constructor.
     *
     * @param int $year
     *
     * @throws InvalidArgumentException
     */
    public function __construct($year)
    {
        if (!Assert::min(0)->validate($year)) {
            throw InvalidArgumentException::invalidYear($year);
        }

        parent::__construct($year);
    }
}
