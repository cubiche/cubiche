<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Comparable;

use Cubiche\Core\Enum\Enum;

/**
 * Sorting Direction enum.
 *
 * @method Direction ASC()
 * @method Direction DESC()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class Direction extends Enum
{
    const __DEFAULT = self::ASC;

    const ASC = 1;
    const DESC = -1;

    /**
     * @return \Cubiche\Core\Comparable\Direction
     */
    public function reverse()
    {
        return new self(-1 * $this->getValue());
    }
}
