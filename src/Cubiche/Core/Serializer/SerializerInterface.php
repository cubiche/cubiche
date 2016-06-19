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
     * Serializes data.
     *
     * @param mixed $object
     *
     * @return string
     *
     * @throws SerializationException
     */
    public function serialize($object);

    /**
     * Deserializes data.
     *
     * @param string $data
     *
     * @return mixed
     *
     * @throws SerializationException
     */
    public function deserialize($data);
}
