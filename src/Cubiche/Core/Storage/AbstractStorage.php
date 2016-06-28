<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Storage;

use Cubiche\Core\Serializer\SerializerInterface;
use Cubiche\Core\Storage\Exception\InvalidKeyException;

/**
 * AbstractStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractStorage
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * AbstractStorage constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Validates that a key is valid.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws InvalidKeyException If the key is invalid.
     */
    protected function validateKey($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw InvalidKeyException::forKey($key);
        }

        return true;
    }
}
