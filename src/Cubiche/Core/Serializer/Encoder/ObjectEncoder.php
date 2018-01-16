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

use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\SerializerAwareInterface;
use Cubiche\Core\Serializer\SerializerAwareTrait;

/**
 * ObjectEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ObjectEncoder implements SerializerAwareInterface, EncoderInterface
{
    use SerializerAwareTrait;

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        try {
            new \ReflectionClass($className);

            return true;
        } catch (\ReflectionException $exception) {
            return $className == 'object';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        $reflection = new \ReflectionClass($object);

        $result = array();
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            $property->setAccessible(true);
            $value = $property->getValue($object);
            $property->setAccessible(false);

            $result[$propertyName] = $this->serializer->serialize($value);
        }

        return array(
            'class' => $reflection->getName(),
            'payload' => $result,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        $reflection = new \ReflectionClass($className);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            if (!array_key_exists($propertyName, $data)) {
                throw SerializationException::propertyNotFound($propertyName, $className);
            }

            $propertyValue = $data[$propertyName];
            if (is_array($propertyValue) && isset($propertyValue['class']) && isset($propertyValue['payload'])) {
                $propertyType = $propertyValue['class'];
                $propertyValue = $propertyValue['payload'];
            } elseif (is_array($propertyValue)
                && isset($propertyValue['datetime'])
                && isset($propertyValue['timezone'])) {
                $propertyType = 'DateTime';
            } else {
                $propertyType = is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue);
            }

            $propertyValue = $this->serializer->deserialize($propertyValue, $propertyType);

            $property->setAccessible(true);
            $property->setValue($object, $propertyValue);
            $property->setAccessible(false);
        }

        return $object;
    }
}
