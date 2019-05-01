<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Event;

/**
 * PreSerializeEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PreSerializeEvent extends Event
{
    const eventName = 'serializer.pre_serialize';

    /**
     * {@inheritdoc}
     */
    public function messageName(): string
    {
        return self::eventName;
    }
}
