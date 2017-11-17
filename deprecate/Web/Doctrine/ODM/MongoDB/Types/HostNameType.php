<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Web\HostName;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * HostNameType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HostNameType extends NativeValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function targetClass()
    {
        return HostName::class;
    }
}
