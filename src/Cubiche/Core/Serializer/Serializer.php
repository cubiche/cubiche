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
    public function serialize($object, $className = null)
    {
        // If the value is an object:
        // - 1. If the object implement the SerializableInterface serialize using the custom methods
        // - 2. If there is a className as a parameter, try to find a decoder using this class name
        // - 3. Try to find a decoder from the object class name
        // - 4. Try to find a 'object' decoder

        // If the value is not an object:
        // - 1. If there is a className as a parameter, try to find a decoder using this class name
        // - 2. Try to find a decoder using the value type

        if (is_object($object)) {
            if ($object instanceof SerializableInterface) {
                return $object->serialize();
            }

            try {
                if ($className !== null) {
                    try {
                        // try to find a className decoder
                        return $this->getDecoder($className)->encode($object);
                    } catch (RuntimeException $e) {
                    }
                }

                // otherwise try to find a custom class decoder
                return $this->getDecoder(get_class($object))->encode($object);
            } catch (RuntimeException $e) {
            }
        } else {
            if ($className !== null) {
                try {
                    // try to find a className decoder
                    return $this->getDecoder($className)->encode($object);
                } catch (RuntimeException $e) {
                }
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
     * @param string $className
     *
     * @return bool
     */
    public function supports($className)
    {
        try {
            return $this->getDecoder($className) !== null;
        } catch (RuntimeException $e) {
            return false;
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
}
