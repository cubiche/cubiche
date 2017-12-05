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

/**
 * NativeEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NativeEncoder implements EncoderInterface
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        return in_array($className, array('boolean', 'integer', 'double', 'string', 'NULL', null));
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 100;
    }
}
