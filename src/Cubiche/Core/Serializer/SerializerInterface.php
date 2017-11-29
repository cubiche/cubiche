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
 * Serializer interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Serializes object to array.
     *
     * @param mixed $object
     *
     * @return array
     */
    public function serialize($object);

    /**
     * Deserializes data to object.
     *
     * @param mixed  $data
     * @param string $className
     *
     * @return object
     */
    public function deserialize($data, $className);

    /**
     * @param string $className
     *
     * @return bool
     */
    public function supports($className);
}
