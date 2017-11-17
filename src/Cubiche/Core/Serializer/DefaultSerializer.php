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
use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Validator;

/**
 * DefaultSerializer class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serialize($object)
    {
        if (is_object($object) && $object instanceof SerializableInterface) {
            return array(
                'class' => get_class($object),
                'payload' => $object->serialize(),
            );
        }

        throw SerializationException::invalidObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $className)
    {
        Validator::assert(
            $data,
            Assert::keySet(
                Assert::key('class', Assert::notBlank()),
                Assert::key('payload', Assert::notBlank())
            )
        );

        if (!in_array(SerializableInterface::class, class_implements($data['class']))) {
            throw SerializationException::invalidClass($data['class']);
        }

        return $data['class']::deserialize($data['payload']);
    }
}
