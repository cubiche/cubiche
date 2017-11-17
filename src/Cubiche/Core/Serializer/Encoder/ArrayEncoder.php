<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Encoder;

use Cubiche\Core\Serializer\SerializerAwareInterface;
use Cubiche\Core\Serializer\SerializerAwareTrait;

/**
 * ArrayEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayEncoder implements SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        return $className == 'array';
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        $result = array();
        foreach ($object as $key => $item) {
            $result[$key] = $this->serializer->serialize($item);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        $result = array();
        foreach ($data as $key => $item) {
            $result[$key] = $this
                ->serializer
                ->deserialize($item, is_object($item) ? get_class($item) : gettype($item))
            ;
        }

        return $result;
    }
}
