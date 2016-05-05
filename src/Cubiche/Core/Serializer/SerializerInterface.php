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

use Cubiche\Core\Serializer\Exception\SerializationException;

/**
 * Serializer interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Serializes data in the appropriate format.
     *
     * @param mixed  $object
     * @param string $format
     * @param array  $context
     *
     * @return string
     *
     * @throws SerializationException
     */
    public function serialize($object, $format, array $context = array());

    /**
     * Deserializes data into the given type.
     *
     * @param string $data
     * @param string $type
     * @param string $format
     * @param array  $context
     *
     * @return mixed
     *
     * @throws SerializationException
     */
    public function deserialize($data, $type, $format, array $context = array());
}
