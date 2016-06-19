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
 * DefaultSerializer class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    public function serialize($data)
    {
        $this->ensureType($data);

        return serialize($data);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data)
    {
        return unserialize($data);
    }
}
