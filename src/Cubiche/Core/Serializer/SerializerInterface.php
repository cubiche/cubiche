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

use Cubiche\Core\Serializer\Encoder\EncoderInterface;

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
     * @param mixed  $object
     * @param string $className
     *
     * @return array
     */
    public function serialize($object, $className = null);

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
     * Adds a serializer encoder. The smaller the priority value,
     * the earlier an encoder will be used in the chain (default is 0).
     *
     * @param EncoderInterface $encoder
     * @param int              $priority
     */
    public function addEncoder(EncoderInterface $encoder, $priority = 0);

    /**
     * @param string $className
     *
     * @return bool
     */
    public function supports($className);
}
