<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Common;

/**
 * QueryLogger interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface QueryLoggerInterface
{
    /**
     * @param array $query
     *
     * @return mixed
     */
    public function logQuery(array $query);
}
