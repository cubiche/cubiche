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
 * Encoder interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EncoderInterface
{
    /**
     * @param string $className
     *
     * @return bool
     */
    public function supports($className);

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function encode($object);

    /**
     * @param mixed  $data
     * @param string $className
     *
     * @return mixed
     */
    public function decode($data, $className);

    /**
     * The higher priority value, the earlier an encoder will be used in the chain (defaults to 0).
     *
     * @return int
     */
    public function priority();
}
