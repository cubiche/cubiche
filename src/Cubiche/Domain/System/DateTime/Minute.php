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
 * Minute.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Minute extends Integer
{
    const MIN_MINUTE = 0;
    const MAX_MINUTE = 59;

    /**
     * @param int $minute
     *
     * @return Minute
     */
    public static function fromNative($minute)
    {
        return new static($minute);
    }

    /**
     * @param \DateTime $date
     *
     * @return Minute
     */
    public static function fromNativeDateTime(\DateTime $date)
    {
        $minute = \intval($date->format('i'));

        return new static($minute);
    }

    /**
     * Minute constructor.
     *
     * @param int $minute
     *
     * @throws InvalidArgumentException
     */
    public function __construct($minute)
    {
        Assert::between(
            $minute,
            self::MIN_MINUTE,
            self::MAX_MINUTE,
            sprintf(
                'Provided minute "%s" must be in range %d - %d',
                $minute,
                self::MIN_MINUTE,
                self::MAX_MINUTE
            )
        );

        parent::__construct($minute);
    }
}
