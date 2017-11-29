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
        if ($className == 'array') {
            return true;
        }

        return '[]' === substr($className, -2) && $this->serializer->supports(substr($className, 0, -2));
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        $result = array();
        foreach ((array) $object as $key => $item) {
            $result[$key] = $this->serializer->serialize($item);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        if ('[]' === substr($className, -2)) {
            $className = substr($className, 0, -2);
        }

        $result = array();
        foreach ($data as $key => $item) {
            if (is_array($item) && isset($item['class']) && isset($item['payload'])) {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item['payload'], $item['class'])
                ;
            } elseif (is_array($item) && isset($item['datetime']) && isset($item['timezone'])) {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item, 'DateTime')
                ;
            } else {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item, $className)
                ;
            }
        }

        return $result;
    }
}
