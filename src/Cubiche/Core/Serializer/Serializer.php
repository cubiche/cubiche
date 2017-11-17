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
use RuntimeException;

/**
 * Serializer class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var EncoderInterface[]
     */
    protected $encoders;

    /**
     * @var EncoderInterface[]
     */
    protected $encoderByType;

    /**
     * ChainDecoder constructor.
     *
     * @param array $encoders
     */
    public function __construct(array $encoders = array())
    {
        $this->encoders = array();
        foreach ($encoders as $encoder) {
            if ($encoder instanceof SerializerAwareInterface) {
                $encoder->setSerializer($this);
            }

            $this->encoders[] = $encoder;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($object)
    {
        if (is_object($object)) {
            if ($object instanceof SerializableInterface) {
                return $object->serialize();
            }

            try {
                // try to find first a custom class decoder
                return $this->getDecoder(get_class($object))->encode($object);
            } catch (RuntimeException $e) {
            }
        }

        // use the default decoders
        return $this
            ->getDecoder(gettype($object))
            ->encode($object)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $className)
    {
        try {
            $reflection = new \ReflectionClass($className);

            // if you are here that means the className is a real class name
            // and you are trying to deserialize an object

            if ($reflection->implementsInterface(SerializableInterface::class)) {
                return $className::deserialize($data);
            }

            try {
                // try to find first a custom class decoder
                return $this
                    ->getDecoder($reflection->getName())
                    ->decode($data, $className)
                ;
            } catch (RuntimeException $e) {
                // go ahead with the object decoder
                return $this
                    ->getDecoder($className)
                    ->decode($data, $className)
                ;
            }
        } catch (\ReflectionException $exception) {
            // if you are here that means the className is not a class name
            // and you are trying to deserialize a non object value
            return $this
                ->getDecoder($className)
                ->decode($data, $className)
            ;
        }
    }

    /**
     * @param string $type
     *
     * @return EncoderInterface
     *
     * @throws RuntimeException
     */
    private function getDecoder($type)
    {
        if (isset($this->encoderByType[$type])) {
            return $this->encoderByType[$type];
        }

        foreach ($this->encoders as $decoder) {
            if ($decoder->supports($type)) {
                $this->encoderByType[$type] = $decoder;

                return $decoder;
            }
        }

        throw new RuntimeException(sprintf('No decoder found for "%s".', $type));
    }

//    /**
//     * @var string ISO-8601 UTC date/time format
//     */
//    const DATETIME_FORMAT = "Y-m-d\TH:i:s.u\Z";

//    /**
//     * ReflectionSerializer constructor.
//     *
//     * @param array $encoders = array()
//     */
//    public function __construct(array $encoders = array())
//    {
//        $this->encoder = new Encoder();
//        $this->encoder->addEncoders(array(
//            $objectEncoder,
//            $arrayEncoder,
//            new NativeEncoder(),
//        ));

//        $this->encoder->addEncoders($encoders);
//    }

//    /**
//     * {@inheritdoc}
//     */
//    public function serialize($object)
//    {
//        return $this->encoder->encode($object);
//    }

//    /**
//     * {@inheritdoc}
//     */
//    public function deserialize(array $data, $className)
//    {
//        return $this->encoder->decode($data, $className);
//    }

//    /**
//     * @param mixed $value
//     *
//     * @return array
//     */
//    protected function recursiveSerialization($value)
//    {
//        if (is_object($value)) {
//            if ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
//                return $this->serializeDateTime($value);
//            }

//            return $this->serializeObject($value);
//        } elseif (is_array($value)) {
//            return $this->serializeArray($value);
//        } else {
//            return $value;
//        }
//    }

//    /**
//     * @param object $object
//     *
//     * @return array
//     */
//    protected function serializeObject($object)
//    {
//        $reflection = new \ReflectionClass($object);

//        $data = array();
//        foreach ($reflection->getProperties() as $property) {
//            $propertyName = $property->getName();

//            $property->setAccessible(true);
//            $value = $property->getValue($object);
//            $property->setAccessible(false);

//            $data[$propertyName] = $this->recursiveSerialization($value);
//        }

//        return array(
//            'class'   => get_class($object),
//            'payload' => $data
//        );
//    }

//    /**
//     * @param array $value
//     *
//     * @return array
//     */
//    protected function serializeArray(array $value)
//    {
//        $data = array();
//        foreach ($value as $key => $item) {
//            $data[$key] = $this->recursiveSerialization($item);
//        }

//        return $data;
//    }

//    /**
//     * @param DateTime|DateTimeImmutable $datetime
//     *
//     * @return array
//     */
//    protected function serializeDateTime($datetime)
//    {
//        $utc = date_create_from_format("U.u", $datetime->format("U.u"), timezone_open("UTC"));

//        return array(
//            'class'    => get_class($datetime),
//            "datetime" => $utc->format(self::DATETIME_FORMAT),
//            "timezone" => $datetime->getTimezone()->getName(),
//        );
//    }

//    /**
//     * {@inheritdoc}
//     */
//    protected function recursiveDeserialization($value)
//    {
//        if (is_array($value) && isset($value['class']) && isset($value['payload'])) {
//            return $this->deserializeObject($value);
//        } elseif(is_array($value) && isset($value['class']) && isset($value['datetime'])) {
//            return $this->deserializeDateTime($value);
//        } elseif (is_array($value)) {
//            return $this->deserializeArray($value);
//        } else {
//            return $value;
//        }
//    }

//    /**
//     * @param array $value
//     *
//     * @return object
//     */
//    protected function deserializeObject(array $value)
//    {
//        $reflection = new \ReflectionClass($value['class']);
//        $object = $reflection->newInstanceWithoutConstructor();

//        foreach ($reflection->getProperties() as $property) {
//            $propertyName = $property->getName();

//            if (!array_key_exists($propertyName, $value['payload'])) {
//                throw SerializationException::propertyNotFound($propertyName, $value['class']);
//            }

//            $propertyValue = $this->recursiveDeserialization($value['payload'][$propertyName]);

//            $property->setAccessible(true);
//            $property->setValue($object, $propertyValue);
//            $property->setAccessible(false);
//        }

//        return $object;
//    }

//    /**
//     * @param array $value
//     *
//     * @return array
//     */
//    protected function deserializeArray(array $value)
//    {
//        $data = array();
//        foreach ($value as $key => $item) {
//            $data[$key] = $this->recursiveDeserialization($item);
//        }

//        return $data;
//    }

//    /**
//     * @param array $value
//     *
//     * @return DateTime|DateTimeImmutable
//     */
//    protected function deserializeDateTime(array $value)
//    {
//        switch ($value['class']) {
//            case "DateTime":
//                $datetime = DateTime::createFromFormat(
//                    self::DATETIME_FORMAT,
//                    $value["datetime"],
//                    timezone_open("UTC")
//                );

//                $datetime->setTimezone(timezone_open($value["timezone"]));

//                return $datetime;
//            case "DateTimeImmutable":
//                $datetime = DateTimeImmutable::createFromFormat(
//                    self::DATETIME_FORMAT,
//                    $value["datetime"],
//                    timezone_open("UTC")
//                );

//                return $datetime->setTimezone(timezone_open($value["timezone"]));
//            default:
//                throw new \RuntimeException("unsupported type: " . $value['class']);
//        }
//    }
}
