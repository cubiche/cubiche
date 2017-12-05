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
 * Iterator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface Iterator extends \Iterator, \Countable
{
    /**
     * Return the first element or null if no elements exist.
     *
     * @return array|object|null
     */
    public function getSingleResult();

    /**
     * Return all elements as an array.
     *
     * @return array
     */
    public function toArray();
}
