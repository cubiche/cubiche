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

use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * ValueObjectEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValueObjectEncoder implements EncoderInterface
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        try {
            $reflection = new \ReflectionClass($className);

            return $reflection->implementsInterface(NativeValueObjectInterface::class);
        } catch (\ReflectionException $exception) {
            return false;
        }
    }

    /**
     * @param NativeValueObjectInterface $object
     *
     * @return mixed
     */
    public function encode($object)
    {
        return $object->toNative();
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        return $className::fromNative($data);
    }
}
