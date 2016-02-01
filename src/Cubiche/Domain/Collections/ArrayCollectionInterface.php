<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Collections;

/**
 * ArrayCollection Interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ArrayCollectionInterface extends CollectionInterface
{
    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|int $key
     * @param mixed      $value
     */
    public function set($key, $value);
}
