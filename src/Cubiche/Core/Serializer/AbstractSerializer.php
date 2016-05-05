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

/**
 * AbstractSerializer class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractSerializer implements SerializerInterface
{
    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function ensureType($value)
    {
        if (is_object($value)) {
            if (!$value instanceof SerializableInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The object must be an instance of %s. Instance of %s given',
                        SerializableInterface::class,
                        is_object($value) ? get_class($value) : gettype($value)
                    )
                );
            }
        }
    }
}
