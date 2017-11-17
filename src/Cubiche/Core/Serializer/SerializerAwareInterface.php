<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer;

/**
 * SerializerAware interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SerializerAwareInterface
{
    /**
     * Sets the serializer.
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer);
}
