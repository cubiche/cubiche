<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder;

/**
 * Serializable interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SerializableInterface
{
    /**
     * @return array
     */
    public function serialize();

    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data);
}
