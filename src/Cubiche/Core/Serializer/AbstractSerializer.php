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
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    public function ensureType($type)
    {
        $reflector = new \ReflectionClass($type);
        if (!$reflector->isSubclassOf(SerializableInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The type must be an instance of %s. Instance of %s given',
                    SerializableInterface::class,
                    $type
                )
            );
        }
    }
}
