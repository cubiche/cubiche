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

use Cubiche\Core\Collections\CollectionInterface;
use Cubiche\Core\Serializer\SerializerAwareInterface;
use Cubiche\Core\Serializer\SerializerAwareTrait;

/**
 * ArrayEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayEncoder implements SerializerAwareInterface, EncoderInterface
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

        return preg_match('/(.+)\\[(.+)\\]/', $className, $output) === 1;

        return '[]' === substr($className, -2) && $this->serializer->supports(substr($className, 0, -2));
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        if ($object === null) {
            return array();
        }

        if (is_object($object)) {
            if ($object instanceof CollectionInterface) {
                $object = $object->toArray();
            }
        }

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
        $classType = null;
        if (preg_match('/(.+)\\[(.+)\\]/', $className, $output) === 1) {
            $classType = $output[1];
            $className = $output[2];
        }

        $result = array();
        foreach ($data as $key => $item) {
            if (is_array($item) && isset($item['class']) && isset($item['payload'])) {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item['payload'], $item['class']);
            } elseif (is_array($item) && isset($item['datetime']) && isset($item['timezone'])) {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item, 'DateTime');
            } else {
                $result[$key] = $this
                    ->serializer
                    ->deserialize($item, $className);
            }
        }

        return $this->normalizeArray($result, $classType);
    }

    /**
     * @param array       $result
     * @param string|null $type
     *
     * @return mixed
     */
    protected function normalizeArray($result, $type = null)
    {
        if ($type === null) {
            return $result;
        }

        switch ($type) {
            case 'array':
                return $result;
            case 'ArrayList':
            case 'ArraySet':
            case 'ArrayHashMap':
                $collectionType = "Cubiche\\Core\\Collections\\ArrayCollection\\$type";

                return new $collectionType($result);
        }
    }
}
